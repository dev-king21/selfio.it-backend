<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->bigInteger('owner_id');
            $table->string('start_time');
            $table->string('end_time');
            $table->enum('orientation', ['Landscape', 'Portrait']);
            $table->boolean('enable');

            $table->integer('style');
            $table->boolean('countdown');
            $table->integer('countdown_time');

            $table->boolean('preview');
            $table->boolean('four_six');
            $table->boolean('gif');
            $table->boolean('gif_animate');
            $table->boolean('boomerang');
            $table->boolean('use_overlay');
            $table->string('overlay1');
            $table->string('overlay2');
            $table->string('overlay3');
            $table->string('overlay4');
            $table->string('print_overlay');

            $table->boolean('green_screen');
            $table->integer('h_value');
            $table->integer('s_value');
            $table->integer('b_value');
            $table->string('green_background');

            $table->boolean('share');
            $table->boolean('whatsapp');
            $table->boolean('whatsapp_msg');
            $table->boolean('sms');
            $table->boolean('sms_msg');
            $table->boolean('email');
            $table->boolean('email_subject');
            $table->boolean('email_msg');

            $table->boolean('block_menu');
            $table->string('password');
            $table->boolean('screen_saver');
            $table->integer('screen_saver_time');
            $table->boolean('printer');
            $table->string('printer_ip')->default('0.0.0.0');
            $table->integer('copy_limit');
            $table->boolean('background');
            $table->enum('background_type', ['Image', 'Video']);
            $table->string('back_content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
