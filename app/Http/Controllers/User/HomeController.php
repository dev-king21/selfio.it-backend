<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Event;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class HomeController extends Controller
{
    /**
     * @var ApiContext
     */
    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.auth:user');
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * Show the User dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::where('owner_id', Auth::guard('user')->user()->id)->orderBy('updated_at', 'desc')->get();
        return view('user.home', compact('events', 'events'));
    }

    /**
     * Show the User plan.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function plan()
    {
        $events = Event::where('owner_id', Auth::guard('user')->user()->id)->orderBy('updated_at', 'desc')->get();
        $plans = Plan::where('type', Auth::guard('user')->user()->type)->orderBy('cost', 'asc')->get();
        return view('user.plan', compact([['plans', 'plans'], ['events', 'events']]));
    }

    /**
     * Change the User plan.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function plan_by_paypal(Request $request)
    {
        Log::error($request);
        $plan = Plan::firstWhere('id', $request['id']);
        $price = $plan->cost;
        $user = Auth::guard('user')->user();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item = new Item();
        $item->setName($plan->id)
            ->setCurrency('EUR')
            ->setQuantity(1)
            ->setPrice($price);
        $item_list = new ItemList();
        $item_list->setItems(array($item));
        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal($price);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($user->name . '(' . $user->email . ')' . '`s payment for ' . config('app.name'));
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('user.paypal_status'))
            ->setCancelUrl(route('user.plan'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        $payment->create($this->_api_context);
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            Session::flash('id', $plan->id);
            Session::flash('events', $request['events']);
            return Redirect::away($redirect_url);
        }
        Session::flash('error', 'Unknown error occurred');
        return Redirect::route('user.plan');
    }

    /**
     * Change the User plan.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paypal_status(Request $request)
    {
        $id = Session::get('id');
        $selected_events = Session::get('events');
        $plan = Plan::firstWhere('id', $id);
        if (!isset($request['PayerID']) || !isset($request['token'])) {
            Session::flash('error', 'Payment failed');
            return Redirect::route('user.plan');
        }
        $payment = Payment::get($request['paymentId'], $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            $user = Auth::guard('user')->user();
            $end_date = today();
            $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $plan->months . ' months'));
            $user->max_devices = $plan->devices;
            $user->max_events = $plan->events;
            $user->end_date = $end_date;
            $user->save();

            $payment = new \App\Models\Payment();
            $payment->user_id = $user->id;
            $payment->type = 'Paypal';
            $payment->devices = $plan->devices;
            $payment->events = $plan->events;
            $payment->months = $plan->months;
            $payment->cost = $plan->cost;
            $payment->save();

            $events = Event::where('owner_id', Auth::guard('user')->user()->id)->get();
            foreach ($events as $event) {
                if ($selected_events == null || str_contains($selected_events, $event->code) !== true) {
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
                    $event->delete();
                } else {
                    if (sizeof(Device::where('event_code', $event->code)->get()) > $plan->devices) {
                        Device::where('event_code', $event->code)->delete();
                        Storage::disk('s3')->deleteDirectory('album/' . $event->code);
                    }
                }
            }

            Session::flash('success', 'Paypal payment success');
            return Redirect::route('user.home');
        }
        Session::flash('error', 'Payment failed');
        return Redirect::route('user.plan');
    }

    /**
     * Change the User plan.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function plan_by_stripe(Request $request)
    {
        $plan = $request['plan'];
        $count = $request['count'];
        if ($plan == 'Month') $price = 9.99 * $count;
        else $price = 99.99 * $count;
        $user = Auth::guard('user')->user();

        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            Charge::create([
                "amount" => $price * 100,
                "currency" => "usd",
                "receipt_email" => $user->email,
                "source" => $request->stripeToken,
                "description" => $user->name . '(' . $user->email . ')' . '`s ' . $count . ' ' . $plan . '(s) ' . ' payment for ' . config('app.name')
            ]);
        } catch (ApiErrorException $e) {
            Session::flash('error', $e->getMessage());
            return Redirect::route('user.plan');
        }

        $user->plan = $plan;
        $end_date = today();
        if ($plan == 'Month')
            $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $count . ' months'));
        else
            $end_date = date('Y-m-d', strtotime($end_date . ' + ' . $count . ' years'));
        $user->end_date = $end_date;
        $user->save();

        $payment = new \App\Models\Payment();
        $payment->name = $user->name;
        $payment->email = $user->email;
        $payment->type = 'Paypal';
        $payment->plan = $plan;
        $payment->count = $count;
        $payment->price = $price;
        $payment->end_date = $end_date;
        $payment->save();
        Session::flash('success', 'Stripe payment for ' . $count . ' ' . $plan . '(s) success');
        return Redirect::route('user.home');
    }
}
