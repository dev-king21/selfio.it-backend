<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'owner_id', 'start_time', 'end_time', 'orientation', 'enable',
        'style', 'countdown', 'countdown_time',
        'preview', 'four_six', 'gif', 'gif_animate', 'boomerang', 'use_overlay',
        'overlay1', 'overlay2', 'overlay3', 'overlay4', 'print_overlay',
        'green_screen', 'h_value', 's_value', 'b_value', 'green_background',
        'share', 'whatsapp', 'whatsapp_msg', 'sms', 'sms_msg', 'email', 'email_subject', 'email_msg',
        'block_menu', 'password', 'screen_saver', 'screen_saver_time', 'printer', 'printer_ip', 'copy_limit',
        'background', 'background_type', 'back_content',
    ];
}
