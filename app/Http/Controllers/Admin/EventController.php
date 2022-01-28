<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index()
    {
        $events = Event::orderBy('id', 'asc')->get();
        return view('admin.events.list', compact('events', 'events'));
    }

    public function event_detail(Request $request)
    {
        $event = Event::firstWhere('id', $request['id']);
        return view('admin.events.detail', compact('event', 'event'));
    }

    public function event_delete(Request $request)
    {
        $id = $request['id'];
        $event = Event::firstWhere('id', $id);
        if ($event != null) {
            Device::where('event_code', $event->code)->delete();
            Storage::disk('s3')->deleteDirectory('album/' . $event->code);
            if ($event->print_overlay != null && str_contains($event->print_overlay, 'http') !== true)
                Storage::disk('public')->delete($event->print_overlay);
            if ($event->overlay1 != null && str_contains($event->overlay1, 'http') !== true)
                Storage::disk('public')->delete($event->overlay1);
            if ($event->overlay2 != null && str_contains($event->overlay2, 'http') !== true)
                Storage::disk('public')->delete($event->overlay2);
            if ($event->overlay3 != null && str_contains($event->overlay3, 'http') !== true)
                Storage::disk('public')->delete($event->overlay3);
            if ($event->overlay4 != null && str_contains($event->overlay4, 'http') !== true)
                Storage::disk('public')->delete($event->overlay4);
            if ($event->green_background != null && str_contains($event->green_background, 'http') !== true)
                Storage::disk('public')->delete($event->green_background);
            if ($event->back_content != null)
                Storage::disk('public')->delete($event->back_content);
            Event::where('id', $id)->delete();
        }
        return Redirect::to(route('admin.events'));
    }
}
