<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PlanController extends Controller
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
        $plans = Plan::orderBy('id', 'asc')->get();
        return view('admin.plans.list', compact('plans', 'plans'));
    }

    public function plan_add(Request $request)
    {
        return view('admin.plans.add');
    }

    public function add_plan(Request $request)
    {
        $plan = new Plan();
        $plan->type = $request['type'];
        $plan->devices = $request['devices'];
        $plan->events = $request['events'];
        $plan->months = $request['months'];
        $plan->cost = $request['cost'];
        $plan->save();
        return Redirect::to(route('admin.plans'));
    }

    public function plan_edit(Request $request)
    {
        $plan = Plan::firstWhere('id', $request['id']);
        return view('admin.plans.edit', compact('plan', 'plan'));
    }

    public function edit_plan(Request $request)
    {
        $plan = Plan::firstWhere('id', $request['id']);
        if ($plan != null) {
            $plan->type = $request['type'];
            $plan->devices = $request['devices'];
            $plan->events = $request['events'];
            $plan->months = $request['months'];
            $plan->cost = $request['cost'];
            $plan->save();
        }
        return Redirect::to(route('admin.plans'));
    }

    public function plan_delete(Request $request)
    {
        Plan::where('id', $request['id'])->delete();
        return Redirect::to(route('admin.plans'));
    }
}
