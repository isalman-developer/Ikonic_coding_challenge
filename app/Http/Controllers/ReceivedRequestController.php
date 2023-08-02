<?php

namespace App\Http\Controllers;

use App\Models\ConnectionRequest;
use Illuminate\Http\Request;

class ReceivedRequestController extends Controller
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

        $receivedRequests = ConnectionRequest::whereReceiverId($user->id)->where('is_accepted', false)->with('sender');

        $receivedRequestsCount = $receivedRequests->count();

        $sentRequestsCount = auth()->user()->sentRequests->count();;

        $connectionsCount = auth()->user()->connections->count();

        $receivedRequests = $receivedRequests->paginate(10);

        if(request()->ajax()){
            return $receivedRequests;
        }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
