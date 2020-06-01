<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route group fro authenticated users only
Route::group(['middleware' =>['auth:api']],function(){
    Route::post('logout','Auth\LoginController@logout');
    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');

    // Upload design
    Route::post('designs/upload','Design\UploadController@upload');

    // Update design
    Route::put('designs/update/{id}','Design\UpdateController@update');
});

// Route group for guests only
Route::group(['middleware'=>['guest:api']],function(){
    Route::post('register','Auth\RegisterController@register');
    Route::post('verification/verified/{user}','Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','Auth\VerificationController@resend')->name('verification.resend');
    Route::post('login','Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');
});

// public routes
Route::get('me','User\UserController@getUser');

// testing route
Route::get('/test',function(){
    return response()->json([
        'message'=>'Test route working !'
    ],200);
});