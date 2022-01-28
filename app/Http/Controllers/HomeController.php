<?php

namespace App\Http\Controllers;

use App\Models\Event;
use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Slideshow selected album.
     *
     * @param $time
     * @return Factory|View
     * @throws Exception
     */
    public function slideshow($time)
    {
        $event = Event::firstWhere('created_at', (new DateTime('@' . $time))->format('Y-m-d H:i:s'));
        if ($event != null) {
            $files = Storage::disk('s3')->files('album/' . $event->code);
            return view('public.slideshow', compact([['event', 'event'], ['files', 'files']]));
        } else
            return back();
    }

    /**
     * View selected album.
     *
     * @param $time
     * @return Factory|View
     * @throws Exception
     */
    public function album(Request $request, $time)
    {
        $event = Event::firstWhere('created_at', (new DateTime('@' . $time))->format('Y-m-d H:i:s'));
        if ($event != null) {
            $files = Storage::disk('s3')->files('album/' . $event->code);
            if (isset($request['id'])) {
                $id = $request['id'];
                $file = $files[$id];
                return view('public.image', compact([['event', 'event'], ['file', 'file']]));
            } else
                return view('public.album', compact([['event', 'event'], ['files', 'files']]));
        } else
            return back();
    }
}
