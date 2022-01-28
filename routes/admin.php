<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin'], function () {
    // Dashboard
    Route::get('/', 'HomeController@index')->name('admin.home');

    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

    // Register
//    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('admin.register');
//    Route::post('register', 'Auth\RegisterController@register');

    // Reset Password
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('admin.password.update');

    // Confirm Password
    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('admin.password.confirm');
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

    // Verify Email
    // Route::get('email/verify', 'Auth\VerificationController@show')->name('admin.verification.notice');
    // Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('admin.verification.verify');
    // Route::post('email/resend', 'Auth\VerificationController@resend')->name('admin.verification.resend');

    Route::get('users', 'UserController@index')->name('admin.users');
    Route::get('user/help', 'UserController@user_help')->name('admin.user.help');
    Route::get('user/edit', 'UserController@user_edit')->name('admin.user.edit');
    Route::post('user/edit', 'UserController@edit_user')->name('admin.user.edit');
    Route::post('user/delete', 'UserController@user_delete')->name('admin.user.delete');

    Route::get('events', 'EventController@index')->name('admin.events');
//    Route::get('event/detail', 'EventController@event_detail')->name('admin.event.detail');
    Route::post('event/delete', 'EventController@event_delete')->name('admin.event.delete');

    Route::get('devices', 'DeviceController@index')->name('admin.devices');
    Route::post('device/delete', 'DeviceController@device_delete')->name('admin.device.delete');

    Route::get('plans', 'PlanController@index')->name('admin.plans');
    Route::get('plan/add', 'PlanController@plan_add')->name('admin.plan.add');
    Route::post('plan/add', 'PlanController@add_plan')->name('admin.plan.add');
    Route::get('plan/edit', 'PlanController@plan_edit')->name('admin.plan.edit');
    Route::post('plan/edit', 'PlanController@edit_plan')->name('admin.plan.edit');
    Route::post('plan/delete', 'PlanController@plan_delete')->name('admin.plan.delete');

    Route::get('payments', 'PaymentController@index')->name('admin.payments');
    Route::post('payment/delete', 'PaymentController@payment_delete')->name('admin.payment.delete');
});
