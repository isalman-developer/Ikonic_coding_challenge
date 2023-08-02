<?php

namespace App\Http\Controllers;

use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConnectionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            $sender = auth()->user();
            $receiver = User::findOrFail($request->id);

            if (!$receiver) {
                return response()->json(['success' => false, 'message' => 'User not found.']);
            }

            // Check if there is an existing request between the sender and receiver
            $existingRequest = ConnectionRequest::where('sender_id', $sender->id)
                ->where('receiver_id', $receiver->id)
                ->first();

            if ($existingRequest) {
                return response()->json(['success' => false, 'message' => 'Request already sent.']);
            }

            // Create a new request
            $request = new ConnectionRequest();
            $request->sender()->associate($sender);
            $request->receiver()->associate($receiver);
            $request->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Request sent successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConnectionRequest  $connectionRequest
     * @return \Illuminate\Http\Response
     */
    public function show(ConnectionRequest $connectionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConnectionRequest  $connectionRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(ConnectionRequest $connectionRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConnectionRequest  $connectionRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConnectionRequest $connectionRequest)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConnectionRequest  $connectionRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConnectionRequest $connectionRequest)
    {
        DB::beginTransaction();
        try {

            $connectionRequest->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Request deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            DB::rollBack();
        }
    }
}
