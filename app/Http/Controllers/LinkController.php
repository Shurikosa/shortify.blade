<?php

namespace App\Http\Controllers;

use App\Http\Services\LinkService;
use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;

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
        try {
            if(!$request->filled('url')) {
                return response()->json([
                    'id' => 'error',
                    'message' => 'Url field cannot be empty.']);
            }
            if(!$this->linkService->isUrlAccessible($request->url)){
                return response()->json([
                    'id' => 'error',
                    'message' => 'The provided URL is not accessible.
                     Please check the URL and try again.',500]);
            }

            if($this->linkService->isUrlAlreadyExist($request->url)) {
                return response()->json([
                    'id' => 'error',
                    'message' =>'The provided URL is already exist.
                     Please check the URL and try again.',500]);
            }

            $this->linkService->addLink($request);
            return response()->json([
                'id' => 'success',
                'message' => 'Link added successfully']);
        } catch (Exception $e) {
            return response()->json([
                'id' => 'error',
                'message' => 'Failed to add link. Please try again later.',
            ], 500);
        }
    }

    public function update(int $id)
    {
        try {
            $this->linkService->updateLink($id);
            $valid_until = $this->linkService->getLinkById($id)->valid_until;
            return response()->json([
                'id' => 'success',
                'message' => 'Link updated successfully',
                'valid_until' => $valid_until->format('Y.m.d H:i'),
                ]);
        } catch (Exception $e) {
            return response()->json([
                'id' => 'error',
                'message' => 'Failed to update link'], 500);
        }
    }

    public function destroy(int $id)
    {
        $this->linkService->deleteLink($id);
        return response()->json([
            'id' => 'success',
            'message' => 'Link deleted successfully']);
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
