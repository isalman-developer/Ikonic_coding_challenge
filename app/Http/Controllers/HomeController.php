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
        $requestType = request()->type ?? 'suggestions';



        $sentRequests = auth()->user()->sentRequests;
        $receivedRequests = auth()->user()->receivedRequests;
        $connections = auth()->user()->connections;

        if($requestType ==  'suggestions' && request()->ajax()){
            // return $suggestions;
        }
        return view('home', compact('suggestions','sentRequests','receivedRequests','connections','requestType'));
    }

    public function sentRequests()
    {
        $sentRequests = auth()->user()->sentRequests;
        return view('sent_requests', compact('sentRequests'));
    }

    public function sendRequest($receiverId)
    {
        $sender = auth()->user();
        $receiver = User::find($receiverId);

        if (!$receiver) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Check if there is an existing request between the sender and receiver
        $existingRequest = ConnectionRequest::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Request already sent.');
        }

        // Create a new request
        $request = new ConnectionRequest();
        $request->sender()->associate($sender);
        $request->receiver()->associate($receiver);
        $request->save();

        return redirect()->back()->with('success', 'Request sent successfully.');
    }

    public function withdrawRequest($requestId)
    {
        $request = ConnectionRequest::find($requestId);

        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Check if the authenticated user is the sender of the request
        if ($request->sender_id !== auth()->user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to withdraw this request.');
        }

        $request->delete();

        return redirect()->back()->with('success', 'Request withdrawn successfully.');
    }

    public function receivedRequests()
    {
        $receivedRequests = auth()->user()->receivedRequests;
        return view('received_requests', compact('receivedRequests'));
    }

    public function acceptRequest($requestId)
    {
        $request = ConnectionRequest::find($requestId);

        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Check if the authenticated user is the receiver of the request
        if ($request->receiver_id !== auth()->user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to accept this request.');
        }

        // Create a new connection between sender and receiver
        $connection = new Connection();
        $connection->user()->associate($request->receiver);
        $connection->connectedUser()->associate($request->sender);
        $connection->save();

        // Delete the request after it's accepted
        $request->delete();

        return redirect()->back()->with('success', 'Request accepted successfully.');
    }

    public function connections()
    {
        $connections = auth()->user()->connections;
        return view('connections', compact('connections'));
    }

    public function removeConnection($connectionId)
    {
        $connection = Connection::find($connectionId);

        if (!$connection) {
            return redirect()->back()->with('error', 'Connection not found.');
        }

        // Check if the authenticated user is one of the connected users
        if ($connection->user_id !== auth()->user()->id && $connection->connected_user_id !== auth()->user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to remove this connection.');
        }

        $connection->delete();

        return redirect()->back()->with('success', 'Connection removed successfully.');
    }

    public function commonConnections($userId)
    {
        $user = auth()->user();
        $targetUser = User::find($userId);

        if (!$targetUser) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Check if the authenticated user is already connected to the target user
        if (!$user->connections->contains($targetUser)) {
            return redirect()->back()->with('error', 'You are not connected to this user.');
        }

        // Get common connections between authenticated user and target user
        $commonConnections = $user->commonConnections($targetUser)->get();

        return view('common_connections', compact('commonConnections', 'targetUser'));
    }
}
