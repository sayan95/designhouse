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

    // Likes and unlikes
    Route::post('designs/{id}/like','Design\DesignController@like');
    Route::get('designs/{id}/liked','Design\DesignController@checkIfUserHasLiked');

    // comment
    Route::post('designs/{id}/comment','Design\CommentController@store');
    Route::put('comments/{id}','Design\CommentController@update');
    Route::delete('comments/{id}','Design\CommentController@destroy');
    Route::post('comments/{id}/like','Design\CommentController@like');
    Route::get('comments/{id}/liked','Design\CommentController@checkIfUserHasLiked');

    // Teams
    Route::get('teams','Teams\TeamsController@index');
    Route::get('teams/{id}','Teams\TeamsController@findById');
    Route::get('users/teams','Teams\TeamsController@fetchUserTeams');
    Route::post('teams','Teams\TeamsController@store');
    Route::put('teams/{id}','Teams\TeamsController@update');
    Route::delete('teams/{id}','Teams\TeamsController@destroy');

    // Invitations
    Route::post('invitations/{team_id}','Teams\InvitationsController@invite');
    Route::post('invitations/{id}/resend','Teams\InvitationsController@resend');
    Route::post('invitations/{id}/respond','Teams\InvitationsController@respond');
    Route::delete('invitations/{id}','Teams\InvitationsController@destroy');
    Route::delete('invitations/{id}/users/{user_id}','Teams\InvitationsController@removeFromTeam');
   
    
    // Chats
    Route::post('chats','Chats\ChatController@sendMessage');
    Route::get('chats','Chats\ChatController@getUserChats');
    Route::get('chats/{id}/messages','Chats\ChatController@getChatMessages');
    Route::put('chats/{id}/mark-as-read','Chats\ChatController@markAsRead');
    Route::delete('messages/{id}','Chats\ChatController@destroyMessage');

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

// get team info
Route::get('teams/slug/{slug}','Teams\TeamsController@findBySlug');





/**
 * testing route
 */
Route::get('/test',function(){
   //
});