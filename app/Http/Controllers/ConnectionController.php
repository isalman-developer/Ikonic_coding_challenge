<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $type = 'connections';

        // Get the count of suggestions for the currently logged-in user.
        $suggestionsCount = $user->suggestions()->count();

        // Get the count of received connection requests for the currently logged-in user.
        $receivedRequestsCount = $user->receivedRequests->count();

        // Get the count of sent connection requests for the currently logged-in user.
        $sentRequestsCount = $user->sentRequests->count();

        // Get all connections for the currently logged-in user (both as sender and receiver).
        $connections = Connection::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('connected_user_id', $user->id);
        })->with('connectedUser', 'user');

        // Count the total number of connections for the currently logged-in user.
        $connectionsCount = $connections->count();

        // Get the entire collection of connected users (both as sender and receiver) before paginating.
        $connectedUsers = $connections->get();

        // Paginate the connections with 10 entries per page.
        $connections = $connections->paginate(10);

        // Create an array to store the IDs of connected users for the currently logged-in user.
        $connectedUserIds = [];

        // Loop through each connected user and add their IDs to the array.
        foreach ($connectedUsers as $connectedUser) {
            // if the currently logged-in user is not receiver
            if ($connectedUser->connected_user_id != $user->id) {
                array_push($connectedUserIds, $connectedUser->connected_user_id);
            }
            // if the currently logged-in user is not sender
            if ($connectedUser->user_id != $user->id) {
                array_push($connectedUserIds, $connectedUser->user_id);
            }
        }

        // Loop through each connection to find the connections in common.
        foreach ($connections as $connection) {
            $connected_ids = [];
            $userConnections = [];

            // Check if the connected user ID is not the currently logged-in user and if it is either the sender or receiver of the currently iterated $connection.
            // Get the collection of connections where the sender or receiver is the connected user(receiver) of the currently iterated $connection.
            if ($connection->connected_user_id != $user->id) {
                $userConnections = Connection::where('user_id', '!=', $user->id)->where('connected_user_id', '!=', $user->id)->where(function ($q) use ($connection) {
                    $q->where('connected_user_id', $connection->connected_user_id)
                        ->orWhere('user_id', $connection->connected_user_id);
                })->get();
            }

            // Check if the user ID is not the currently logged-in user and if it is either the sender or receiver of the currently iterated $connection.
            // Get the collection of connections where the sender or receiver is the user(sender) of the currently iterated $connection.
            if ($connection->user_id != $user->id) {
                $userConnections = Connection::where('user_id', '!=', $user->id)->where('connected_user_id', '!=', $user->id)->where(function ($q) use ($connection) {
                    $q->where('connected_user_id', $connection->user_id)
                        ->orWhere('user_id', $connection->user_id);
                })->get();
            }

            // Loop through each userConnection to find ids and also checking that the the sender or receiver of the current $connection should not get in the array of connected_ids because we are finding the ids of its connection so, it should not present in its own connection listig
            foreach ($userConnections as $userConnection) {
                // Check if the connected user ID is not the currently logged-in user and if it is not the receiver of the iterated $connection.
                if ($userConnection->connected_user_id != $user->id && ($userConnection->connected_user_id != $connection->connected_user_id && $userConnection->connected_user_id != $connection->user_id)) {
                    array_push($connected_ids, $userConnection->connected_user_id);
                }

                // Check if the user ID is not the currently logged-in user and if it is not the sender of the iterated $connection.
                if ($userConnection->user_id != $user->id && ($userConnection->user_id != $connection->user_id && $userConnection->user_id != $connection->connected_user_id)) {
                    array_push($connected_ids, $userConnection->user_id);
                }
            }

            // Find the intersection of connectedUserIds and connected_ids to get common connection IDs. So taking only the ids that matches with the collection of the connected ids of the currently logged in user and the collection of the connected ids of $connection.
            $commonConnectionIds = array_intersect($connectedUserIds, $connected_ids);

            // Get the users with the common connection IDs (excluding the currently logged-in user) and paginate the result.
            $connection->commonConnections = User::whereIn('id', $commonConnectionIds)->where('id', '!=', $user->id)->paginate(10);
        }

        // If the request is an AJAX request, return the paginated connections.
        if (request()->ajax()) {
            return $connections;
        }


        return view('home', compact(
            'type',
            'connections',
            'connectionsCount',
            'receivedRequestsCount',
            'sentRequestsCount',
            'suggestionsCount'
        ));
    }

    /**
     * To Display the common connection pagination
     *
     * @param Request $request
     * @return void
     */
    public function getCommonConnection(Request $request)
    {
        $user = auth()->user();
        $userId = $request->query('id') ?? 1;

        // Fetch all connected users where the currently logged-in user is either sender or receiver. later we will compare it with the collection of the connection of the $userId
        $connectedUsers = Connection::where(function ($q) use ($user) {
            $q->where('connected_user_id', $user->id)
                ->orWhere('user_id', $user->id);
        })->get();

        $connectedUserIds = [];

        // Create an array of connected user IDs excluding the currently logged-in user because its his collection, it shouldn't come in its collection.
        foreach ($connectedUsers as $connectedUser) {
            if ($connectedUser->connected_user_id != $user->id) {
                array_push($connectedUserIds, $connectedUser->connected_user_id);
            }
            if ($connectedUser->user_id != $user->id) {
                array_push($connectedUserIds, $connectedUser->user_id);
            }
        }

        $connected_ids = [];
        // Fetch all connections of the currently selected user ($userId) excluding the currently logged-in user.
        $userConnections = Connection::where('user_id', '!=', $user->id)->where('connected_user_id', '!=', $user->id)->where(function ($q) use ($userId) {
            $q->where('connected_user_id', $userId)
                ->orWhere('user_id', $userId);
        })->get();

        // Create an array of connected user IDs of the selected user ($userId) excluding the currently logged-in user and the selected user ($userId) itself. because currently-logged in user should not be there on its own listing.
        foreach ($userConnections as $userConnection) {
            if ($userConnection->connected_user_id != $user->id && $userConnection->connected_user_id != $userId) {
                array_push($connected_ids, $userConnection->connected_user_id);
            }
            if ($userConnection->user_id != $user->id && $userConnection->user_id != $userId) {
                array_push($connected_ids, $userConnection->user_id);
            }
        }

        // Find the common connection IDs between the currently logged-in user and the selected user ($userId).
        $commonConnectionIds = array_intersect($connectedUserIds, $connected_ids);

        // Fetch user records of the common connections and paginate the results and ignore currently logged-in user.
        $data = User::whereIn('id', $commonConnectionIds)->where('id', '!=', $user->id)->paginate(10);

        // Return the paginated results.
        return $data;
    }

    /**
     * To get the listing of suggestions
     *
     * @return void
     */
    public function getSuggestions()
    {
        // Get the currently logged-in user.
        $user = auth()->user();
        $type = 'suggestions';

        // Get the query for suggestions for the currently logged-in user.
        $suggestions = $user->suggestions();

        // Count the number of suggestions for the currently logged-in user.
        $suggestionsCount = $suggestions->count();

        // Count the number of sent connection requests for the currently logged-in user.
        $sentRequestsCount = $user->sentRequests->count();

        // Count the number of received connection requests for the currently logged-in user.
        $receivedRequestsCount = $user->receivedRequests()->count();

        // Get all connections for the currently logged-in user (both as sender and receiver).
        $connections = Connection::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('connected_user_id', $user->id);
        })->with('connectedUser', 'user');

        // Count the total number of connections for the currently logged-in user.
        $connectionsCount = $connections->count();

        // Paginate the suggestions for better presentation (10 suggestions per page).
        $suggestions = $suggestions->paginate(10);

        // If the request is made through AJAX, return the paginated suggestions.
        if (request()->ajax()) {
            return $suggestions;
        }

        // If the request is not AJAX, return the home view with relevant data.
        return view('home', compact(
            'type',
            'suggestions',
            'suggestionsCount',
            'sentRequestsCount',
            'receivedRequestsCount',
            'connectionsCount'
        ));
    }

    /**
     * To get the listing of received requests
     *
     * @return void
     */
    public function getReceivedRequests()
    {
        // Get the currently logged-in user.
        $user = auth()->user();
        $type = 'received_requests';

        // Get the count of suggestions for the currently logged-in user.
        $suggestionsCount = $user->suggestions()->count();

        // Get all connection requests where the currently logged-in user is the receiver and eager load the sender relationship.
        $receivedRequests = ConnectionRequest::whereReceiverId($user->id)->with('sender');

        // Count the total number of received connection requests for the currently logged-in user.
        $receivedRequestsCount = $receivedRequests->count();

        // Count the total number of sent connection requests for the currently logged-in user.
        $sentRequestsCount = auth()->user()->sentRequests->count();

        // Get all connections for the currently logged-in user (both as sender and receiver) and eager load the connectedUser and user relationships.
        $connections = Connection::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('connected_user_id', $user->id);
        })->with('connectedUser', 'user');

        // Count the total number of connections for the currently logged-in user.
        $connectionsCount = $connections->count();

        // Paginate the received connection requests for better presentation (10 requests per page).
        $receivedRequests = $receivedRequests->paginate(10);

        // If the request is made through AJAX, return the paginated received connection requests.
        if (request()->ajax()) {
            return $receivedRequests;
        }

        // If the request is not AJAX, return the home view with relevant data.
        return view('home', compact(
            'type',
            'receivedRequests',
            'receivedRequestsCount',
            'sentRequestsCount',
            'suggestionsCount',
            'connectionsCount'
        ));
    }

    /**
     * To get the listing of sent requests
     *
     * @return void
     */
    public function getSentRequests()
    {
        // Get the currently logged-in user.
        $user = auth()->user();
        $type = 'sent_requests';

        // Get the count of suggestions for the currently logged-in user.
        $suggestionsCount = $user->suggestions()->count();

        // Get all connection requests where the currently logged-in user is the sender and eager load the receiver relationship.
        $sentRequests = ConnectionRequest::whereSenderId($user->id)->with('receiver');

        // Count the total number of sent connection requests for the currently logged-in user.
        $sentRequestsCount = $sentRequests->count();

        // Count the total number of received connection requests for the currently logged-in user.
        $receivedRequestsCount = $user->receivedRequests()->count();

        // Get all connections for the currently logged-in user (both as sender and receiver) and eager load the connectedUser and user relationships.
        $connections = Connection::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('connected_user_id', $user->id);
        })->with('connectedUser', 'user');

        // Count the total number of connections for the currently logged-in user.
        $connectionsCount = $connections->count();

        // Paginate the sent connection requests for better presentation (10 requests per page).
        $sentRequests = $sentRequests->paginate(10);

        // If the request is made through AJAX, return the paginated sent connection requests.
        if (request()->ajax()) {
            return $sentRequests;
        }

        // If the request is not AJAX, return the home view with relevant data.
        return view('home', compact(
            'type',
            'sentRequests',
            'sentRequestsCount',
            'suggestionsCount',
            'receivedRequestsCount',
            'connectionsCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // getting the connection request
            $request = ConnectionRequest::findOrFail($request->input('id'));

            // Create a new connection between sender and receiver
            $connection = new Connection();
            $connection->user()->associate($request->sender);
            $connection->connectedUser()->associate($request->receiver);
            $connection->save();

            // Delete the request after it's accepted
            $request->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Request deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Connection  $connection
     * @return \Illuminate\Http\Response
     */
    public function show(Connection $connection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Connection  $connection
     * @return \Illuminate\Http\Response
     */
    public function edit(Connection $connection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Connection  $connection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Connection $connection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Connection  $connection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Connection $connection)
    {
        DB::beginTransaction();
        try {

            $connection->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Connection deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            DB::rollBack();
        }
    }
}
