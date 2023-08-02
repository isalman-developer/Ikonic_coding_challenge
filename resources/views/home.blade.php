@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/helper.js') }}?v={{ time() }}" defer></script>
    <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>

    <div class="container">
        <x-dashboard />

        @if ($type == 'suggestions')
            <x-suggestion :suggestions="$suggestions" :sentRequestsCount="$sentRequestsCount" :receivedRequestsCount="$receivedRequestsCount" :connectionsCount="$connectionsCount" :suggestionsCount="$suggestionsCount"/>
        @endif

        @if ($type == 'sent_requests')
            <x-sent_requests :sentRequests="$sentRequests" :sentRequestsCount="$sentRequestsCount" :suggestionsCount="$suggestionsCount" :receivedRequestsCount="$receivedRequestsCount" :connectionsCount="$connectionsCount" :suggestionsCount="$suggestionsCount"/>
        @endif

        @if ($type == 'received_requests')
            <x-received_requests :receivedRequests="$receivedRequests" :receivedRequestsCount="$receivedRequestsCount" :sentRequestsCount="$sentRequestsCount" :suggestionsCount="$suggestionsCount"  :connectionsCount="$connectionsCount" :suggestionsCount="$suggestionsCount"/>
        @endif

    </div>
@endsection

