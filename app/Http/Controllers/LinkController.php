<?php

namespace App\Http\Controllers;

use App\Http\Services\LinkService;
use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
  protected LinkService $linkService;

  public function __construct(LinkService $linkService)
  {
    $this->linkService = $linkService;
  }

    public function index()
    {
        $links = $this->linkService->getAllLinks();
        return view('dashboard', ['links' => $links]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        try {
            if(!$this->linkService->isUrlAccessible($request->url)){
                return response()->json(['message' => 'The provided URL is not accessible.
                                            Please check the URL and try again.']);
            }

            if($this->linkService->isUrlAlreadyExist($request->url)) {
                return response()->json(['message' =>'The provided URL is already exist.
                                            Please check the URL and try again.']);
            }

            $this->linkService->addLink($request);
            return response()->json(['message' => 'Link added successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to add link'], 500);
        }


    }

    public function update(int $id)
    {
        try {
            $this->linkService->updateLink($id);
            return response()->json(['message' => 'Link updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update link'], 500);
        }
    }

    public function destroy(int $id)
    {
        $this->linkService->deleteLink($id);
        return response()->json(['message' => 'Link deleted successfully']);
    }

    public function redirect($short_link)
    {
        $link = Link::where('short_link', $short_link)->firstOrFail();
        if($this->linkService->checkShortLink($link)){
            return redirect()->route('dashboard')->with('error', 'This link is invalid or expired.');
        }
        $link->increment('click_count');
        return redirect()->away($link->url);

    }
}
