<?php

use Illuminate\Support\Facades\Route;

// Define the routes for user connections
Route::middleware(['web'])->group(function () {
    // Route for displaying suggestions
    Route::get('/suggestions', 'UserConnectionController@showSuggestions')->name('suggestions');

    // Route for sending a connection request
    Route::post('/connect/{user}', 'UserConnectionController@sendConnectionRequest')->name('connect');

    // Route for displaying sent requests
    Route::get('/sent-requests', 'UserConnectionController@showSentRequests')->name('sent-requests');

    // Route for withdrawing a connection request
    Route::delete('/withdraw-request/{connectionRequest}', 'UserConnectionController@withdrawRequest')->name('withdraw-request');

    // Route for displaying received requests
    Route::get('/received-requests', 'UserConnectionController@showReceivedRequests')->name('received-requests');

    // Route for accepting a connection request
    Route::put('/accept-request/{connectionRequest}', 'UserConnectionController@acceptRequest')->name('accept-request');

    // Route for displaying user connections
    Route::get('/connections', 'UserConnectionController@showConnections')->name('connections');

    // Route for removing a connection
    Route::delete('/remove-connection/{connection}', 'UserConnectionController@removeConnection')->name('remove-connection');

    // Route for displaying connections in common with a user
    Route::get('/connections-in-common/{user}', 'UserConnectionController@showCommonConnections')->name('connections-in-common');
});
