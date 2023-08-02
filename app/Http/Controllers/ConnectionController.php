<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\ConnectionRequest;
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
        $type = 'received_requests';

        // function defined inside the user model for return the suggestion query
        $suggestionsCount = $user->suggestions()->count();

        $receivedRequestsCount = $user->sentRequests->count();

        $sentRequestsCount = $user->sentRequests->count();;

        $connections = Connection::whereUserId($user->id);

        $connectionsCount = $connections->count();

        $connectionsCount = $user->connections->count();

        if(request()->ajax()){
            return $receivedRequests;
        }

        return view('home', compact(
            'type',
            'receivedRequests',
            'sentRequestsCount',
            'suggestionsCount',
            'connections',
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
            $request = ConnectionRequest::findOrFail($request->input('id'));

            // Create a new connection between sender and receiver
            $connection = new Connection();
            $connection->user()->associate($request->receiver);
            $connection->connectedUser()->associate($request->sender);
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
        //
    }
}
