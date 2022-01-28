<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
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
        $payments = Payment::orderBy('id', 'asc')->get();
        return view('admin.payments.list', compact('payments', 'payments'));
    }

    public function payment_delete(Request $request)
    {
        Payment::where('id', $request['id'])->delete();
        return Redirect::to(route('admin.payments'));
    }
}
