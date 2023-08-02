<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $type = 'suggestions';

        $suggestions = User::whereDoesntHave('connections')
            ->whereDoesntHave('sentRequests', function ($query) use ($user) {
                $query->where('receiver_id', $user->id);
            })
            ->whereDoesntHave('receivedRequests', function ($query) use ($user) {
                $query->where('sender_id', $user->id);
            })
            ->where('id', '!=', $user->id);

        $suggestionsCount = $suggestions->count();
        $sentRequestsCount = auth()->user()->sentRequests->count();
        $receivedRequestsCount = auth()->user()->receivedRequests->count();;
        $connectionsCount = auth()->user()->connections->count();
        $suggestions = $suggestions->paginate(10);
        if(request()->ajax()){
            info($suggestions);
            return $suggestions;
        }

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