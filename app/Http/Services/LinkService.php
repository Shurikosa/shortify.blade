<?php

namespace App\Http\Services;

use App\Models\Link;
use App\Utils\ShortLinkGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class LinkService
{
    public function getAllLinks(): Collection
    {
        return Link::query()->where('user_id', Auth::id())->get();
    }

    public function addLink(Request $request): void
    {
        $link = new Link();
        $link->user_id = Auth::id();
        $link->url = $request->url;
        $link->short_link = ShortLinkGenerator::generateShortLink($request->url);
        $link->click_count = 0;
        $link->valid_until = now()->addMinutes(1);
        $link->save();
    }

    public function updateLink(int $id): void
    {
        $link = $this->getLinkById($id);
        $link->valid_until = now()->addMinutes(5);
        $link->save();
    }
    public function getLinkById(int $id): Link
    {
        return Link::query()->findOrFail($id);
    }

    public function deleteLink(int $id): void
    {
        $link = Link::query()->findOrFail($id);
        $link->delete();
    }



    public function isUrlAccessible(string $url): bool
    {
        try {
            $response = Http::get($url);
            return $response->successful();
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function isUrlAlreadyExist(string $url): bool
    {
        return Link::query()->where('url', $url)->where('user_id', Auth::id())->exists();
    }

    public function checkShortLink($shortLink): bool
    {
        return !$shortLink || ($shortLink->valid_until && $shortLink->valid_until->isPast());
    }

}
