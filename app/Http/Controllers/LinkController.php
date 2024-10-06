<?php

namespace App\Http\Controllers;

use App\Http\Services\LinkService;
use App\Models\Link;
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

        if(!$this->linkService->isUrlAccessible($request->url)){
            return redirect()->back()->with('error', 'The provided URL is not accessible.
                                            Please check the URL and try again.');
        }

        if($this->linkService->isUrlAlreadyExist($request->url)) {
        return redirect()->back()->with('error', 'The provided URL is already exist.
                                            Please check the URL and try again.');
        }

        $this->linkService->addLink($request);
        return redirect()->back()->with('success', 'Link added successfully');

    }

    public function update(Request $request , int $id)
    {
        $link = Link::query()->findOrFail($id);
        $link->valid_until = now()->addMinutes(1);
        return redirect()->back();
    }

    public function destroy(int $id)
    {
        $link = Link::query()->findOrFail($id);
        $link->delete();
        return redirect()->back();
    }

    public function redirect(){

    }
}
