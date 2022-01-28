<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DeviceController extends Controller
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
        $devices = Device::orderBy('id', 'asc')->get();
        return view('admin.devices.list', compact('devices', 'devices'));
    }

    public function device_delete(Request $request)
    {
        Device::where('id', $request['id'])->delete();
        return Redirect::to(route('admin.devices'));
    }
}
