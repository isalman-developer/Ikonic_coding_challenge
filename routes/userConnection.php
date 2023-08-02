<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\SentConnectionController;
use App\Http\Controllers\ConnectionRequestController;
use App\Http\Controllers\ReceivedConnectionController;


// Define the routes for user connections
Route::middleware(['auth'])->group(function () {

    // individual routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // resource routes
    Route::resource('connections', ConnectionController::class);
    Route::resource('connection-requests', ConnectionRequestController::class);
    Route::resource('suggestions', SuggestionController::class);
    Route::resource('sent-connections', SentConnectionController::class);
    Route::resource('received-connections', ReceivedConnectionController::class);


    // Route for sending a connection request
    Route::post('/send-request/{receiverId}', [App\Http\Controllers\HomeController::class, 'sendRequest'])
        ->name('send-request');

    // Route for displaying sent requests
    Route::get('/sent-requests', [App\Http\Controllers\HomeController::class, 'sentRequests'])
        ->name('sent-requests');

    // Route for withdrawing a connection request
    Route::post('/withdraw-request/{requestId}', [App\Http\Controllers\HomeController::class, 'withdrawRequest'])
        ->name('withdraw-request');

    // Route for displaying received requests
    Route::get('/received-requests', [App\Http\Controllers\HomeController::class, 'receivedRequests'])
        ->name('received-requests');

    // Route for accepting a connection request
    Route::post('/accept-request/{requestId}', [App\Http\Controllers\HomeController::class, 'acceptRequest'])
        ->name('accept-request');

    // Route for displaying user connections
    // Route::get('/connections', [App\Http\Controllers\HomeController::class, 'connections'])
    //     ->name('connections');

    // Route for removing a connection
    Route::post('/remove-connection/{connectionId}', [App\Http\Controllers\HomeController::class, 'removeConnection'])
        ->name('remove-connection');

    // Route for displaying connections in common with a user
    Route::get('/common-connections/{userId}', [App\Http\Controllers\HomeController::class, 'commonConnections'])
        ->name('common-connections');
});
