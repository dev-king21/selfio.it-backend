<?php

namespace App\Http\Controllers\User;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Event;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
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
        $this->middleware('user.auth:user');
    }

    /**
     * View new event layout.
     *
     * @return Renderable
     */
    public function event_add()
    {
        return view('user.event.add');
    }

    /**
     * Add new event.
     *
     * @return RedirectResponse
     */
    public function add_event(Request $request)
    {
        $event = new Event();
        $event->name = $request['event_name'];
        do {
            $event->code = Helper::generateRandomNumber(3) . "-" . Helper::generateRandomNumber(3);
        } while (Event::firstWhere('code', $event->code) != null);
        $event->owner_id = Auth::guard('user')->user()->id;
        $event->start_time = $request['start_time'];
        $event->end_time = $request['end_time'];
        $event->orientation = $request['orientation'];
        $event->enable = false;

        $event->style = $request['style'];
        $event->countdown = isset($request['countdown']) ? true : false;
        $event->countdown_time = $event->countdown ? $request['countdown_time'] : 0;

        $event->four_six = $event->style == 3 && isset($request['four_six']) ? true : false;
        $event->gif = isset($request['gif']) && $event->style != 1 ? true : false;
        $event->preview = ($event->style == 1 || ($event->gif && isset($request['overlay0']))) && isset($request['preview']) ? true : false;
        $event->gif_animate = isset($request['gif_animate']) && $event->gif ? true : false;
        $event->boomerang = isset($request['boomerang']) && $event->gif ? true : false;
        $event->use_overlay = isset($request['use_overlay']) && $event->boomerang ? true : false;

        $event->green_screen = isset($request['green_screen']) ? true : false;
        $event->h_value = $event->green_screen ? $request['h_value'] : 0;
        $event->s_value = $event->green_screen ? $request['s_value'] : 0;
        $event->b_value = $event->green_screen ? $request['b_value'] : 0;

        $event->share = isset($request['share']) ? true : false;
        $event->whatsapp = isset($request['whatsapp']) ? true : false;
        $event->whatsapp_msg = isset($request['whatsapp']) && $request['whatsapp_msg'] ? $request['whatsapp_msg'] : '';
        $event->sms = isset($request['sms']) ? true : false;
        $event->sms_msg = isset($request['sms']) && $request['sms_msg'] ? $request['sms_msg'] : '';
        $event->email = isset($request['email']) ? true : false;
        $event->email_subject = isset($request['email']) && $request['email_subject'] ? $request['email_subject'] : '';
        $event->email_msg = isset($request['email']) && $request['email_msg'] ? $request['email_msg'] : '';

        $event->block_menu = isset($request['block_menu']) ? true : false;
        $event->password = $event->block_menu && $request['password'] ? $request['password'] : '';
        $event->screen_saver = isset($request['screen_saver']) ? true : false;
        $event->screen_saver_time = $event->screen_saver ? $request['screen_saver_time'] : 0;
        $event->printer = isset($request['printer']) ? true : false;
        $event->printer_ip = isset($request['printer_ip']) && $event->printer ? $request['printer_ip'] : '0.0.0.0';
        $event->copy_limit = isset($request['copy_limit']) && $event->printer ? $request['copy_limit'] : 1;
        $event->background = isset($request['background']) ? true : false;
        $event->background_type = $request['background_type'];
        $event->back_content = '';
        if ($event->background && $event->background_type === 'Image') {
            $image = $request['back_content'];
            if (strpos($image, 'image/png') > 0) {
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.png';
            }
            if (strpos($image, 'image/jpg') > 0) {
                $image = str_replace('data:image/jpg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.jpg';
            }
            if (strpos($image, 'image/jpeg') > 0) {
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.jpg';
            }
            if (strpos($image, 'image/gif') > 0) {
                $image = str_replace('data:image/gif;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.gif';
            }
            if ($event->back_content != '')
                Storage::disk('public')->put($event->back_content, base64_decode($image));
        } else if ($event->background) {
            $video = $request['back_content'];
            if (strpos($video, 'video/mp4') > 0) {
                $video = str_replace('data:video/mp4;base64,', '', $video);
                $video = str_replace(' ', '+', $video);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.mp4';
            }
            if (strpos($video, 'video/ogg') > 0) {
                $video = str_replace('data:video/ogg;base64,', '', $video);
                $video = str_replace(' ', '+', $video);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.ogg';
            }
            if (strpos($video, 'video/webm') > 0) {
                $video = str_replace('data:video/webm;base64,', '', $video);
                $video = str_replace(' ', '+', $video);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.webm';
            }
            if ($event->back_content != '')
                Storage::disk('public')->put($event->back_content, base64_decode($video));
        }

        $overlay0 = $request['overlay0'];
        $overlay1 = $request['overlay1'];
        $overlay2 = $request['overlay2'];
        $overlay3 = $request['overlay3'];
        $overlay4 = $request['overlay4'];
        $overlay5 = $event->green_screen ? $request['overlay5'] : '';
        $event->print_overlay = '';
        $event->overlay1 = '';
        $event->overlay2 = '';
        $event->overlay3 = '';
        $event->overlay4 = '';
        $event->green_background = '';

        if (strpos($overlay5, 'http') === 0 || strpos($overlay5, 'overlay_images/') === 0) {
            $event->green_background = $overlay5;
        } else {
            if (strpos($overlay5, 'image/png') > 0) {
                $overlay5 = str_replace('data:image/png;base64,', '', $overlay5);
                $overlay5 = str_replace(' ', '+', $overlay5);
                $green_background = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                Storage::disk('public')->put($green_background, base64_decode($overlay5));
                $event->green_background = $green_background;
            }
            if (strpos($overlay5, 'image/jpg') > 0) {
                $overlay5 = str_replace('data:image/jpg;base64,', '', $overlay5);
                $overlay5 = str_replace(' ', '+', $overlay5);
                $green_background = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                Storage::disk('public')->put($green_background, base64_decode($overlay5));
                $event->green_background = $green_background;
            }
            if (strpos($overlay5, 'image/jpeg') > 0) {
                $overlay5 = str_replace('data:image/jpeg;base64,', '', $overlay5);
                $overlay5 = str_replace(' ', '+', $overlay5);
                $green_background = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                Storage::disk('public')->put($green_background, base64_decode($overlay5));
                $event->green_background = $green_background;
            }
        }

        if (strpos($overlay0, 'http') === 0 || strpos($overlay0, 'overlay_images/') === 0) {
            $event->print_overlay = $overlay0;
        } else {
            if (strpos($overlay0, 'image/png') > 0) {
                $overlay0 = str_replace('data:image/png;base64,', '', $overlay0);
                $overlay0 = str_replace(' ', '+', $overlay0);
                $event->print_overlay = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
            }
            if (strpos($overlay0, 'image/jpg') > 0) {
                $overlay0 = str_replace('data:image/jpg;base64,', '', $overlay0);
                $overlay0 = str_replace(' ', '+', $overlay0);
                $event->print_overlay = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
            }
            if (strpos($overlay0, 'image/jpeg') > 0) {
                $overlay0 = str_replace('data:image/jpeg;base64,', '', $overlay0);
                $overlay0 = str_replace(' ', '+', $overlay0);
                $event->print_overlay = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
            }
            if ($event->print_overlay != '')
                Storage::disk('public')->put($event->print_overlay, base64_decode($overlay0));
        }

        $overlay_count = 0;
        if ($event->gif) {
            switch ($event->style) {
                case 2:
                    $overlay_count = 4;
                    break;
                case 3:
                    if ($event->orientation == 'Portrait') {
                        $overlay_count = 3;
                    } else {
                        $overlay_count = 4;
                    }
                    break;
            }
        }

        if ($overlay_count > 0) {
            if (strpos($overlay1, 'http') === 0 || $overlay1 === '') {
                $event->overlay1 = $overlay1;
            } else {
                if (strpos($overlay1, 'image/png') > 0) {
                    $overlay1 = str_replace('data:image/png;base64,', '', $overlay1);
                    $overlay1 = str_replace(' ', '+', $overlay1);
                    $event->overlay1 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                }
                if (strpos($overlay1, 'image/jpg') > 0) {
                    $overlay1 = str_replace('data:image/jpg;base64,', '', $overlay1);
                    $overlay1 = str_replace(' ', '+', $overlay1);
                    $event->overlay1 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                }
                if (strpos($overlay1, 'image/jpeg') > 0) {
                    $overlay1 = str_replace('data:image/jpeg;base64,', '', $overlay1);
                    $overlay1 = str_replace(' ', '+', $overlay1);
                    $event->overlay1 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                }
                if ($event->overlay1 != '')
                    Storage::disk('public')->put($event->overlay1, base64_decode($overlay1));
            }
            if ($overlay_count > 1) {
                if (strpos($overlay2, 'http') === 0 || strpos($overlay2, 'overlay_images/') === 0) {
                    $event->overlay2 = $overlay2;
                } else {
                    if (strpos($overlay2, 'image/png') > 0) {
                        $overlay2 = str_replace('data:image/png;base64,', '', $overlay2);
                        $overlay2 = str_replace(' ', '+', $overlay2);
                        $overlayName2 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                        Storage::disk('public')->put($overlayName2, base64_decode($overlay2));
                        $event->overlay2 = $overlayName2;
                    }
                    if (strpos($overlay2, 'image/jpg') > 0) {
                        $overlay2 = str_replace('data:image/jpg;base64,', '', $overlay2);
                        $overlay2 = str_replace(' ', '+', $overlay2);
                        $overlayName2 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName2, base64_decode($overlay2));
                        $event->overlay2 = $overlayName2;
                    }
                    if (strpos($overlay2, 'image/jpeg') > 0) {
                        $overlay2 = str_replace('data:image/jpeg;base64,', '', $overlay2);
                        $overlay2 = str_replace(' ', '+', $overlay2);
                        $overlayName2 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName2, base64_decode($overlay2));
                        $event->overlay2 = $overlayName2;
                    }
                }
                if (strpos($overlay3, 'http') === 0 || strpos($overlay3, 'overlay_images/') === 0) {
                    $event->overlay3 = $overlay3;
                } else {
                    if (strpos($overlay3, 'image/png') > 0) {
                        $overlay3 = str_replace('data:image/png;base64,', '', $overlay3);
                        $overlay3 = str_replace(' ', '+', $overlay3);
                        $overlayName3 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                        Storage::disk('public')->put($overlayName3, base64_decode($overlay3));
                        $event->overlay3 = $overlayName3;
                    }
                    if (strpos($overlay3, 'image/jpg') > 0) {
                        $overlay3 = str_replace('data:image/jpg;base64,', '', $overlay3);
                        $overlay3 = str_replace(' ', '+', $overlay3);
                        $overlayName3 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName3, base64_decode($overlay3));
                        $event->overlay3 = $overlayName3;
                    }
                    if (strpos($overlay3, 'image/jpeg') > 0) {
                        $overlay3 = str_replace('data:image/jpeg;base64,', '', $overlay3);
                        $overlay3 = str_replace(' ', '+', $overlay3);
                        $overlayName3 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName3, base64_decode($overlay3));
                        $event->overlay3 = $overlayName3;
                    }
                }
            }
            if ($overlay_count == 4) {
                if (strpos($overlay4, 'http') === 0 || strpos($overlay4, 'overlay_images/') === 0) {
                    $event->overlay4 = $overlay4;
                } else {
                    if (strpos($overlay4, 'image/png') > 0) {
                        $overlay4 = str_replace('data:image/png;base64,', '', $overlay4);
                        $overlay4 = str_replace(' ', '+', $overlay4);
                        $overlayName4 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                        Storage::disk('public')->put($overlayName4, base64_decode($overlay4));
                        $event->overlay4 = $overlayName4;
                    }
                    if (strpos($overlay4, 'image/jpg') > 0) {
                        $overlay4 = str_replace('data:image/jpg;base64,', '', $overlay4);
                        $overlay4 = str_replace(' ', '+', $overlay4);
                        $overlayName4 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName4, base64_decode($overlay4));
                        $event->overlay4 = $overlayName4;
                    }
                    if (strpos($overlay4, 'image/jpeg') > 0) {
                        $overlay4 = str_replace('data:image/jpeg;base64,', '', $overlay4);
                        $overlay4 = str_replace(' ', '+', $overlay4);
                        $overlayName4 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName4, base64_decode($overlay4));
                        $event->overlay4 = $overlayName4;
                    }
                }
            }
        }
        $event->save();
        return Redirect::to(route('user.home'));
    }

    /**
     * Delete selected event.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete_event(Request $request)
    {
        $event = Event::firstWhere('id', $request['id']);
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
            $event->delete();
        }
        return Redirect::to(route('user.home'));
    }

    /**
     * Enable/disable selected event.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function enable_event(Request $request)
    {
        $event = Event::firstWhere('id', $request['id']);
        $event->enable = isset($request['enable']) ? true : false;
        $event->save();

        return Redirect::to(route('user.home'));
    }

    /**
     * View edit event layout.
     *
     * @return Renderable
     */
    public function event_edit(Request $request)
    {
        $event = Event::firstWhere('id', $request['id']);
        return view('user.event.edit', compact('event', 'event'));
    }

    /**
     * Edit event.
     *
     * @return RedirectResponse
     */
    public function edit_event(Request $request)
    {
        $event = Event::firstWhere('id', $request['id']);
        if ($event == null) {
            Session::flash('error', 'Such event doesn\'t exist');
            return back();
        }
        $event->name = $request['event_name'];
        $event->owner_id = Auth::guard('user')->user()->id;
        $event->start_time = $request['start_time'];
        $event->end_time = $request['end_time'];
        $event->orientation = $request['orientation'];

        $event->style = $request['style'];
        $event->countdown = isset($request['countdown']) ? true : false;
        $event->countdown_time = $event->countdown ? $request['countdown_time'] : 0;

        $event->four_six = $event->style == 3 && isset($request['four_six']) ? true : false;
        $event->gif = isset($request['gif']) && $event->style != 1 ? true : false;
        $event->preview = ($event->style == 1 || ($event->gif && isset($request['overlay0']))) && isset($request['preview']) ? true : false;
        $event->gif_animate = isset($request['gif_animate']) && $event->gif ? true : false;
        $event->boomerang = isset($request['boomerang']) && $event->gif ? true : false;
        $event->use_overlay = isset($request['use_overlay']) && $event->boomerang ? true : false;

        $event->green_screen = isset($request['green_screen']) ? true : false;
        $event->h_value = $event->green_screen ? $request['h_value'] : 0;
        $event->s_value = $event->green_screen ? $request['s_value'] : 0;
        $event->b_value = $event->green_screen ? $request['b_value'] : 0;

        $event->share = isset($request['share']) ? true : false;
        $event->whatsapp = isset($request['whatsapp']) ? true : false;
        $event->whatsapp_msg = isset($request['whatsapp']) && $request['whatsapp_msg'] ? $request['whatsapp_msg'] : '';
        $event->sms = isset($request['sms']) ? true : false;
        $event->sms_msg = isset($request['sms']) && $request['sms_msg'] ? $request['sms_msg'] : '';
        $event->email = isset($request['email']) ? true : false;
        $event->email_subject = isset($request['email']) && $request['email_subject'] ? $request['email_subject'] : '';
        $event->email_msg = isset($request['email']) && $request['email_msg'] ? $request['email_msg'] : '';

        $event->block_menu = isset($request['block_menu']) ? true : false;
        $event->password = $event->block_menu && $request['password'] ? $request['password'] : '';
        $event->screen_saver = isset($request['screen_saver']) ? true : false;
        $event->screen_saver_time = $event->screen_saver ? $request['screen_saver_time'] : 0;
        $event->printer = isset($request['printer']) ? true : false;
        $event->printer_ip = isset($request['printer_ip']) && $event->printer ? $request['printer_ip'] : '0.0.0.0';
        $event->copy_limit = isset($request['copy_limit']) && $event->printer ? $request['copy_limit'] : 1;
        $event->background = isset($request['background']) ? true : false;
        $event->background_type = $request['background_type'];
        $image = $request['back_content'];
        if ($image == null || (str_contains($image, '_background.') !== true && str_contains($event->back_content, '_background.') === true))
            Storage::disk('public')->delete($event->back_content);
        $event->back_content = '';
        if ($event->background && $event->background_type === 'Image') {
            if (strpos($image, 'image/png') > 0) {
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.png';
                file_put_contents('storage/' . $event->back_content, base64_decode($image));
            }
            if (strpos($image, 'image/jpg') > 0) {
                $image = str_replace('data:image/jpg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.jpg';
                file_put_contents('storage/' . $event->back_content, base64_decode($image));
            }
            if (strpos($image, 'image/jpeg') > 0) {
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.jpg';
                file_put_contents('storage/' . $event->back_content, base64_decode($image));
            }
            if (strpos($image, 'image/gif') > 0) {
                $image = str_replace('data:image/gif;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.gif';
                file_put_contents('storage/' . $event->back_content, base64_decode($image));
            }
        } else if ($event->background) {
            $video = $request['back_content'];
            if (strpos($video, 'video/mp4') > 0) {
                $video = str_replace('data:video/mp4;base64,', '', $video);
                $video = str_replace(' ', '+', $video);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.mp4';
                file_put_contents('storage/' . $event->back_content, base64_decode($video));
            }
            if (strpos($video, 'video/ogg') > 0) {
                $video = str_replace('data:video/ogg;base64,', '', $video);
                $video = str_replace(' ', '+', $video);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.ogg';
                file_put_contents('storage/' . $event->back_content, base64_decode($video));
            }
            if (strpos($video, 'video/webm') > 0) {
                $video = str_replace('data:video/webm;base64,', '', $video);
                $video = str_replace(' ', '+', $video);
                $event->back_content = Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(5) . '_' . 'background.webm';
                file_put_contents('storage/' . $event->back_content, base64_decode($video));
            }
        }

        $overlay0 = $request['overlay0'];
        $overlay1 = $request['overlay1'];
        $overlay2 = $request['overlay2'];
        $overlay3 = $request['overlay3'];
        $overlay4 = $request['overlay4'];
        $overlay5 = $event->green_screen ? $request['overlay5'] : '';
        Log::error($request['overlay5']);
        if (strpos($overlay0, 'overlay_images/') !== 0 && strpos($event->print_overlay, 'overlay_images/') === 0)
            Storage::disk('public')->delete($event->print_overlay);
        if (strpos($overlay1, 'overlay_images/') !== 0 && strpos($event->overlay1, 'overlay_images/') === 0)
            Storage::disk('public')->delete($event->overlay1);
        if (strpos($overlay2, 'overlay_images/') !== 0 && strpos($event->overlay2, 'overlay_images/') === 0)
            Storage::disk('public')->delete($event->overlay2);
        if (strpos($overlay3, 'overlay_images/') !== 0 && strpos($event->overlay3, 'overlay_images/') === 0)
            Storage::disk('public')->delete($event->overlay3);
        if (strpos($overlay4, 'overlay_images/') !== 0 && strpos($event->overlay4, 'overlay_images/') === 0)
            Storage::disk('public')->delete($event->overlay4);
        if (strpos($overlay5, 'overlay_images/') !== 0 && strpos($event->green_background, 'overlay_images/') === 0)
            Storage::disk('public')->delete($event->green_background);
        $event->print_overlay = '';
        $event->overlay1 = '';
        $event->overlay2 = '';
        $event->overlay3 = '';
        $event->overlay4 = '';
        $event->green_background = '';

        if (strpos($overlay5, 'http') === 0 || strpos($overlay5, 'overlay_images/') === 0) {
            $event->green_background = $overlay5;
        } else {
            if (strpos($overlay5, 'image/png') > 0) {
                $overlay5 = str_replace('data:image/png;base64,', '', $overlay5);
                $overlay5 = str_replace(' ', '+', $overlay5);
                $green_background = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                Storage::disk('public')->put($green_background, base64_decode($overlay5));
                $event->green_background = $green_background;
            }
            if (strpos($overlay5, 'image/jpg') > 0) {
                $overlay5 = str_replace('data:image/jpg;base64,', '', $overlay5);
                $overlay5 = str_replace(' ', '+', $overlay5);
                $green_background = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                Storage::disk('public')->put($green_background, base64_decode($overlay5));
                $event->green_background = $green_background;
            }
            if (strpos($overlay5, 'image/jpeg') > 0) {
                $overlay5 = str_replace('data:image/jpeg;base64,', '', $overlay5);
                $overlay5 = str_replace(' ', '+', $overlay5);
                $green_background = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                Storage::disk('public')->put($green_background, base64_decode($overlay5));
                $event->green_background = $green_background;
            }
        }

        if (strpos($overlay0, 'http') === 0 || strpos($overlay0, 'overlay_images/') === 0) {
            $event->print_overlay = $overlay0;
        } else {
            if (strpos($overlay0, 'image/png') > 0) {
                $overlay0 = str_replace('data:image/png;base64,', '', $overlay0);
                $overlay0 = str_replace(' ', '+', $overlay0);
                $printOverlay = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                Storage::disk('public')->put($printOverlay, base64_decode($overlay0));
                $event->print_overlay = $printOverlay;
            }
            if (strpos($overlay0, 'image/jpg') > 0) {
                $overlay0 = str_replace('data:image/jpg;base64,', '', $overlay0);
                $overlay0 = str_replace(' ', '+', $overlay0);
                $printOverlay = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                Storage::disk('public')->put($printOverlay, base64_decode($overlay0));
                $event->print_overlay = $printOverlay;
            }
            if (strpos($overlay0, 'image/jpeg') > 0) {
                $overlay0 = str_replace('data:image/jpeg;base64,', '', $overlay0);
                $overlay0 = str_replace(' ', '+', $overlay0);
                $printOverlay = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                Storage::disk('public')->put($printOverlay, base64_decode($overlay0));
                $event->print_overlay = $printOverlay;
            }
        }

        $overlay_count = 0;
        if ($event->gif) {
            switch ($event->style) {
                case 2:
                    $overlay_count = 4;
                    break;
                case 3:
                    if ($event->orientation == 'Portrait') {
                        $overlay_count = 3;
                    } else {
                        $overlay_count = 4;
                    }
                    break;
            }
        }

        if ($overlay_count > 0) {
            if (strpos($overlay1, 'http') === 0 || strpos($overlay1, 'overlay_images/') === 0 || $overlay1 === '') {
                $event->overlay1 = $overlay1;
            } else {
                if (strpos($overlay1, 'image/png') > 0) {
                    $overlay1 = str_replace('data:image/png;base64,', '', $overlay1);
                    $overlay1 = str_replace(' ', '+', $overlay1);
                    $overlayName1 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                    Storage::disk('public')->put($overlayName1, base64_decode($overlay1));
                    $event->overlay1 = $overlayName1;
                }
                if (strpos($overlay1, 'image/jpg') > 0) {
                    $overlay1 = str_replace('data:image/jpg;base64,', '', $overlay1);
                    $overlay1 = str_replace(' ', '+', $overlay1);
                    $overlayName1 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                    Storage::disk('public')->put($overlayName1, base64_decode($overlay1));
                    $event->overlay1 = $overlayName1;
                }
                if (strpos($overlay1, 'image/jpeg') > 0) {
                    $overlay1 = str_replace('data:image/jpeg;base64,', '', $overlay1);
                    $overlay1 = str_replace(' ', '+', $overlay1);
                    $overlayName1 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                    Storage::disk('public')->put($overlayName1, base64_decode($overlay1));
                    $event->overlay1 = $overlayName1;
                }
            }
            if ($overlay_count > 1) {
                if (strpos($overlay2, 'http') === 0 || strpos($overlay2, 'overlay_images/') === 0) {
                    $event->overlay2 = $overlay2;
                } else {
                    if (strpos($overlay2, 'image/png') > 0) {
                        $overlay2 = str_replace('data:image/png;base64,', '', $overlay2);
                        $overlay2 = str_replace(' ', '+', $overlay2);
                        $overlayName2 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                        Storage::disk('public')->put($overlayName2, base64_decode($overlay2));
                        $event->overlay2 = $overlayName2;
                    }
                    if (strpos($overlay2, 'image/jpg') > 0) {
                        $overlay2 = str_replace('data:image/jpg;base64,', '', $overlay2);
                        $overlay2 = str_replace(' ', '+', $overlay2);
                        $overlayName2 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName2, base64_decode($overlay2));
                        $event->overlay2 = $overlayName2;
                    }
                    if (strpos($overlay2, 'image/jpeg') > 0) {
                        $overlay2 = str_replace('data:image/jpeg;base64,', '', $overlay2);
                        $overlay2 = str_replace(' ', '+', $overlay2);
                        $overlayName2 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName2, base64_decode($overlay2));
                        $event->overlay2 = $overlayName2;
                    }
                }
                if (strpos($overlay3, 'http') === 0 || strpos($overlay3, 'overlay_images/') === 0) {
                    $event->overlay3 = $overlay3;
                } else {
                    if (strpos($overlay3, 'image/png') > 0) {
                        $overlay3 = str_replace('data:image/png;base64,', '', $overlay3);
                        $overlay3 = str_replace(' ', '+', $overlay3);
                        $overlayName3 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                        Storage::disk('public')->put($overlayName3, base64_decode($overlay3));
                        $event->overlay3 = $overlayName3;
                    }
                    if (strpos($overlay3, 'image/jpg') > 0) {
                        $overlay3 = str_replace('data:image/jpg;base64,', '', $overlay3);
                        $overlay3 = str_replace(' ', '+', $overlay3);
                        $overlayName3 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName3, base64_decode($overlay3));
                        $event->overlay3 = $overlayName3;
                    }
                    if (strpos($overlay3, 'image/jpeg') > 0) {
                        $overlay3 = str_replace('data:image/jpeg;base64,', '', $overlay3);
                        $overlay3 = str_replace(' ', '+', $overlay3);
                        $overlayName3 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName3, base64_decode($overlay3));
                        $event->overlay3 = $overlayName3;
                    }
                }
            }
            if ($overlay_count == 4) {
                if (strpos($overlay4, 'http') === 0 || strpos($overlay4, 'overlay_images/') === 0) {
                    $event->overlay4 = $overlay4;
                } else {
                    if (strpos($overlay4, 'image/png') > 0) {
                        $overlay4 = str_replace('data:image/png;base64,', '', $overlay4);
                        $overlay4 = str_replace(' ', '+', $overlay4);
                        $overlayName4 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.png';
                        Storage::disk('public')->put($overlayName4, base64_decode($overlay4));
                        $event->overlay4 = $overlayName4;
                    }
                    if (strpos($overlay4, 'image/jpg') > 0) {
                        $overlay4 = str_replace('data:image/jpg;base64,', '', $overlay4);
                        $overlay4 = str_replace(' ', '+', $overlay4);
                        $overlayName4 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName4, base64_decode($overlay4));
                        $event->overlay4 = $overlayName4;
                    }
                    if (strpos($overlay4, 'image/jpeg') > 0) {
                        $overlay4 = str_replace('data:image/jpeg;base64,', '', $overlay4);
                        $overlay4 = str_replace(' ', '+', $overlay4);
                        $overlayName4 = 'overlay_images/' . Auth::guard('user')->user()->id . '_' . Helper::generateRandomString(10) . '.jpg';
                        Storage::disk('public')->put($overlayName4, base64_decode($overlay4));
                        $event->overlay4 = $overlayName4;
                    }
                }
            }
        }
        $event->save();
        return Redirect::to(route('user.home'));
    }
}
