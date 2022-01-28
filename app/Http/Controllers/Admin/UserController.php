<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Event;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.users.list', compact('users', 'users'));
    }

    public function user_help(Request $request)
    {
        $user = User::firstWhere('id', $request['id']);
        Auth::guard('user')->login($user);
        return Redirect::to(route('user.home'));
    }

    public function user_edit(Request $request)
    {
        $user = User::firstWhere('id', $request['id']);
        return view('admin.users.edit', compact('user', 'user'));
    }

    public function edit_user(Request $request)
    {
        $user = User::firstWhere('id', $request['id']);
        $user->type = $request['type'];
        $user->max_devices = $request['max_devices'];
        $user->max_events = $request['max_events'];
        $user->end_date = $request['end_date'];
        $user->save();

        return Redirect::to(route('admin.users'));
    }

    public function user_delete(Request $request)
    {
        $id = $request['id'];
        Payment::where('user_id', $id)->delete();
        $events = Event::where('owner_id', $id)->get();
        foreach ($events as $index => $event) {
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
        }
        Event::where('owner_id', $id)->delete();
        User::where('id', $id)->delete();
        return Redirect::to(route('admin.users'));
    }
}
