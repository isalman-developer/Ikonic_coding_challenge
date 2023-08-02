@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/helper.js') }}?v={{ time() }}" defer></script>
    <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>

    <div class="container">
        <x-dashboard />

        @if ($type == 'suggestions')
            <x-suggestion
            :suggestions="$suggestions"
            :suggestionsCount="$suggestionsCount"
            :sentRequestsCount="$sentRequestsCount"
            :receivedRequestsCount="$receivedRequestsCount"
            :connectionsCount="$connectionsCount"
            />
        @endif

        @if ($type == 'sent_requests')
            <x-sent_requests
            :suggestionsCount="$suggestionsCount"
            :sentRequests="$sentRequests"
            :sentRequestsCount="$sentRequestsCount"
            :receivedRequestsCount="$receivedRequestsCount"
            :connectionsCount="$connectionsCount"
            />
        @endif

        @if ($type == 'received_requests')
            <x-received_requests
            :suggestionsCount="$suggestionsCount"
            :sentRequestsCount="$sentRequestsCount"
            :receivedRequests="$receivedRequests"
            :receivedRequestsCount="$receivedRequestsCount"
            :connectionsCount="$connectionsCount"
            />
        @endif

        @if ($type == 'connections')
            <x-connection
            :suggestionsCount="$suggestionsCount"
            :sentRequestsCount="$sentRequestsCount"
            :receivedRequestsCount="$receivedRequestsCount"
            :connections="$connections"
            :connectionsCount="$connectionsCount"
            />
        @endif

    </div>
@endsection

