<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'User'], function() {
    // Dashboard
    Route::get('/', 'HomeController@index')->name('user.home');

    Route::get('plan', 'HomeController@plan')->name('user.plan');
    Route::post('plan_by_paypal', 'HomeController@plan_by_paypal')->name('user.plan_by_paypal');
    Route::get('paypal_status', 'HomeController@paypal_status')->name('user.paypal_status');
    Route::post('plan_by_stripe', 'HomeController@plan_by_stripe')->name('user.plan_by_stripe');

    Route::get('event/add', 'EventController@event_add')->name('user.event.add');
    Route::post('event/add', 'EventController@add_event')->name('user.event.add');
    Route::get('event/edit', 'EventController@event_edit')->name('user.event.edit');
    Route::post('event/edit', 'EventController@edit_event')->name('user.event.edit');
    Route::post('event/delete', 'EventController@delete_event')->name('user.event.delete');
    Route::post('event/enable', 'EventController@enable_event')->name('user.event.enable');

    Route::get('album', 'AlbumController@album')->name('user.album');
    Route::post('album/event/remove', 'AlbumController@removeImage')->name('user.album.event.remove');

    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('user.logout');

    // Register
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('user.register');
    Route::post('register', 'Auth\RegisterController@register');

    // Reset Password
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('user.password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('user.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('user.password.update');

    // Confirm Password
    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('user.password.confirm');
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

    // Verify Email
    // Route::get('email/verify', 'Auth\VerificationController@show')->name('user.verification.notice');
    // Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('user.verification.verify');
    // Route::post('email/resend', 'Auth\VerificationController@resend')->name('user.verification.resend');
});
