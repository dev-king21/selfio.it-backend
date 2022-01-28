<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AlbumController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.auth:user');
    }

    /**
     * View new event layout.
     *
     * @return Renderable
     */
    public function album(Request $request)
    {
        if (isset($request['id'])) {
            $event = Event::firstWhere('id', $request['id']);
            return view('user.album.event', compact('event', 'event'));
        } else {
            $events = Event::where('owner_id', Auth::guard('user')->user()->id)->orderBy('updated_at', 'desc')->get();
            return view('user.album.home', compact('events', 'events'));
        }
    }

    /**
     * Delete selected image.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeImage(Request $request)
    {
        if (isset($request['url']))
            Storage::disk('s3')->delete($request['url']);

        return back();
    }
}
