<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Route group fro authenticated users only
 */
Route::group(['middleware' =>['auth:api']],function(){
    Route::post('logout','Auth\LoginController@logout');
    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');

    // Upload design
    Route::post('designs/upload','Design\UploadController@upload');

    // Update design
    Route::put('designs/update/{id}','Design\UpdateController@update');

    // Delete design
    Route::delete('designs/delete/{id}','Design\DeleteController@destroy');


    //comment
    Route::post('designs/{id}/comment','Design\CommentController@store');
    Route::put('comments/{id}','Design\CommentController@update');
    Route::delete('comments/{id}','Design\CommentController@destroy');
});




/**
 * Route group for guests only
 */
Route::group(['middleware'=>['guest:api']],function(){
    Route::post('register','Auth\RegisterController@register');
    Route::post('verification/verified/{user}','Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','Auth\VerificationController@resend')->name('verification.resend');
    Route::post('login','Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');
});



/**
 * public routes
 */ 
Route::get('me','User\UserController@getUser');

//  route for designs
Route::get('designs','Design\DesignController@index');
Route::get('designs/{id}','Design\DesignController@findById');
Route::get('designs_where/{col}/{val}','Design\DesignController@findByColName');
Route::get('designs_where_first/{col}/{val}','Design\DesignController@findByColNameFirst');
Route::get('designs/paginate/{no}','Design\DesignController@pagination');

// get all users
Route::get('users','User\UserController@index');







/**
 * testing route
 */
Route::get('/test',function(){
    //
});