<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\ConnectionRequestController;

// Define the routes for user connections
Route::middleware(['auth'])->group(function () {

    // individual routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/connections/common/connection', [ConnectionController::class, 'getCommonConnection'])->name('connections.common');
    Route::get('/suggestions', [ConnectionController::class, 'getSuggestions'])->name('connections.suggestions');
    Route::get('/sent-requests', [ConnectionController::class, 'getSentRequests'])->name('connections.sent.requests');
    Route::get('/received-requests', [ConnectionController::class, 'getReceivedRequests'])->name('connections.received.requests');
    // resource routes
    Route::resource('connections', ConnectionController::class);
    Route::resource('connection-requests', ConnectionRequestController::class);

});
