<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('connections.suggestions');
    }

}
