<?php

namespace App\Http\Controllers;

use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $type = 'sent_requests';

        // function defined inside the user model for return the suggestion query
        $suggestionsCount = $user->suggestions()->count();

        $sentRequests = ConnectionRequest::whereSenderId($user->id)->where(['is_accepted' => false])->with('receiver');

        $sentRequestsCount = $sentRequests->count();

        $receivedRequestsCount = $user->receivedRequests()->where('is_accepted', false)->count();;

        $connectionsCount = $user->connections->count();

        $sentRequests = $sentRequests->paginate(10);

        if(request()->ajax()){
            return $sentRequests;
        }

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
