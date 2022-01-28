<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function getEvent(Request $request)
    {
        if (isset($request['event_code']) && isset($request['android_id'])) {
            $event = Event::firstWhere('code', $request['event_code']);
            if ($event != null) {
                $owner = User::firstWhere('id', $event->owner_id);
                if ($owner->end_date < date("Y-m-d"))
                    return array("status" => "201", "result" => "Account is expired");
                else {
                    $device = Device::firstWhere([['event_code', $request['event_code']], ['android_id', $request['android_id']]]);
                    if ($device == null) {
                        $device = new Device();
                        $device->event_code = $request['event_code'];
                        $device->android_id = $request['android_id'];
                        $devices = Device::where('event_code', $request['event_code'])->orderBy('device_id', 'asc')->get();
                        if (sizeof($devices) >= $owner->max_devices)
                            return array("status" => "201", "result" => "Reached max devices");
                        else {
                            $device_id = 1;
                            foreach ($devices as $index => $dev)
                                if ($device_id == $dev->device_id)
                                    $device_id = $dev->device_id + 1;
                                else
                                    break;
                            $device->device_id = $device_id;
                            $device->save();
                            $device = Device::firstWhere([['event_code', $request['event_code']], ['android_id', $request['android_id']]]);
                            return array("status" => "200", "event" => $event, "device_id" => $device->device_id, "business" => $owner->type == "Business");
                        }
                    }
//                    else if ($device->device_id > $owner->max_devices)
//                        return array("status" => "201", "result" => "Reached max devices");
                    else
                        return array("status" => "200", "event" => $event, "device_id" => $device->device_id, "business" => $owner->type == "Business");
                }
            } else
                return array("status" => "201", "result" => "No such event");
        } else
            return array("status" => "201", "result" => "Json Params are missing");
    }

    public function uploadToServer(Request $request)
    {
        if (isset($request['code']) && isset($request['filename']) && isset($request['image'])) {
            if (strpos($request['filename'], ".gif") > 0) {
                $filepath = 'album/' . $request['code'] . "/gif/";
                $filename = str_replace(".gif", "", $request['filename']);
            } else {
                $filepath = 'album/' . $request['code'] . "/";
                $filename = str_replace(".jpg", "", $request['filename']);
            }
            $files = array_filter(Storage::disk('s3')->files($filepath),
                function ($item) use ($filepath, $filename) {
                    return strpos($item, $filepath . $filename . "-") === 0;
                }
            );
            foreach ($files as $file)
                Storage::disk('s3')->delete($file);

            $filename = str_replace(".", "-" . date("YmdHis") . ".", $request['filename']);
            if (file_exists($request['image'])) {
                Storage::disk('s3')->put($filepath . $filename, file_get_contents($request['image']));
                return array("status" => "200", "result" => 'success');
            } else
                return array("status" => "200", "result" => 'file not found');
        } else
            return array("status" => "201", "result" => "Json Params are missing");
    }

    public function sendWhatsappMsg(Request $request)
    {
        if (isset($request['code']) && isset($request['phone']) && isset($request['body']) && isset($request['filename'])) {
            $event = Event::firstWhere('code', $request['code']);
            if (strpos($request['filename'], ".gif") > 0) {
                $filepath = 'album/' . $request['code'] . "/gif/";
                $filename = str_replace(".gif", "", $request['filename']);
            } else {
                $filepath = 'album/' . $request['code'] . "/";
                $filename = str_replace(".jpg", "", $request['filename']);
            }
            $files = array_filter(Storage::disk('s3')->files($filepath),
                function ($item) use ($filepath, $filename) {
                    return strpos($item, $filepath . $filename . "-") === 0;
                }
            );
            foreach ($files as $file)
                Storage::disk('s3')->delete($file);

            $filename = str_replace(".", "-" . date("YmdHis") . ".", $request['filename']);
            Storage::disk('s3')->put($filepath . $filename, base64_decode($request['body']));
            $response = Http::get(env('API4BOT_STATUS_URL'))->body();
            $status = json_decode($response);
            if ($status->accountStatus == 'authenticated') {
                $response = Http::post(env('API4BOT_SENDMSG_URL'), [
                    'phone' => $request['phone'],
                    'body' => 'Hai appena fatto un Selfie, desideri ricevere la tua foto a questo numero?',
                ])->body();
                $status = json_decode($response, true);
                if ($status['sent'] == true) {
                    if (strpos($filename, ".gif") > 0) {
                        $body = Storage::disk('s3')->url($filepath . $filename);
                        $response = Http::post(env('API4BOT_SENDLINK_URL'), [
                            'phone' => $request['phone'],
                            'body' => $body,
                            'previewBase64' => 'data:image/png;base64,' . base64_encode(file_get_contents(asset('images/user/preview.png'))),
                            'title' => $filename,
                            'description' => $event->whatsapp_msg,
                        ])->body();
                    } else {
                        $body = 'data:image/jpg;base64,' . $request['body'];
                        $response = Http::post(env('API4BOT_SENDFILE_URL'), [
                            'phone' => $request['phone'],
                            'body' => $body,
                            'filename' => $filename,
                            'caption' => $event->whatsapp_msg,
                        ])->body();
                    }
                    $status = json_decode($response, true);
                    if ($status['sent'] == true)
                        return array("status" => "200", "result" => 'success');
                    else
                        return array("status" => "201", "result" => 'failed');
                } else
                    return array("status" => "201", "result" => 'failed');
            } else
                return array("status" => "201", "result" => $status->accountStatus);
        } else
            return array("status" => "201", "result" => "Json Params are missing");
    }

    public function sendSMS(Request $request)
    {
        if (isset($request['code']) && isset($request['phone']) && isset($request['body']) && isset($request['filename'])) {
            $event = Event::firstWhere('code', $request['code']);
            if (strpos($request['filename'], ".gif") > 0) {
                $filepath = 'album/' . $request['code'] . "/gif/";
                $filename = str_replace(".gif", "", $request['filename']);
            } else {
                $filepath = 'album/' . $request['code'] . "/";
                $filename = str_replace(".jpg", "", $request['filename']);
            }
            $files = array_filter(Storage::disk('s3')->files($filepath),
                function ($item) use ($filepath, $filename) {
                    return strpos($item, $filepath . $filename . "-") === 0;
                }
            );
            foreach ($files as $file)
                Storage::disk('s3')->delete($file);

            $filename = str_replace(".", "-" . date("YmdHis") . ".", $request['filename']);
            Storage::disk('s3')->put($filepath . $filename, base64_decode($request['body']));
            $response = Http::asJson()->post(env('NETFUN_API_URL'), [
                'text' => $event->sms_msg . '\n' . Storage::disk('s3')->url($filepath . $filename),
                'numbers' => $request['phone'],
            ])->body();
            $status = json_decode($response, true);
            if (!empty($status['queue_id']))
                return array("status" => "200", "result" => 'success');
            else
                return array("status" => "201", "result" => 'failed');
        } else
            return array("status" => "201", "result" => "Json Params are missing");
    }

    public function sendEmail(Request $request)
    {
        if (isset($request['code']) && isset($request['email']) && isset($request['body']) && isset($request['filename'])) {
            $event = Event::firstWhere('code', $request['code']);
            if (strpos($request['filename'], ".gif") > 0) {
                $filepath = 'album/' . $request['code'] . "/gif/";
                $filename = str_replace(".gif", "", $request['filename']);
            } else {
                $filepath = 'album/' . $request['code'] . "/";
                $filename = str_replace(".jpg", "", $request['filename']);
            }
            $files = array_filter(Storage::disk('s3')->files($filepath),
                function ($item) use ($filepath, $filename) {
                    return strpos($item, $filepath . $filename . "-") === 0;
                }
            );
            foreach ($files as $file)
                Storage::disk('s3')->delete($file);

            $filename = str_replace(".", "-" . date("YmdHis") . ".", $request['filename']);
            Storage::disk('s3')->put($filepath . $filename, base64_decode($request['body']));
            $email = $request['email'];
            $url = Storage::disk('s3')->url($filepath . $filename);
            $data = array("msg" => $event->email_msg);
            Mail::send('user.mail', $data, function ($message) use ($url, $email, $event) {
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
                $message->to($email);
                $message->attach($url);
                $message->subject($event->email_subject);
            });
            return array("status" => "200", "result" => 'success');
        } else
            return array("status" => "201", "result" => "Json Params are missing");
    }
}
