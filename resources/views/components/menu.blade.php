<div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" @checked(request()->routeIs('connections.suggestions') || request()->routeIs('home.*'))>
    <a href="{{ route('connections.suggestions') }}" class="btn btn-outline-primary" for="btnradio1"
        id="get_suggestions_btn">
        Suggestions ({{ $suggestionsCount }})
    </a>

    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
        @checked(request()->routeIs('connections.sent.requests'))>
    <a href="{{ route('connections.sent.requests') }}" class="btn btn-outline-primary" for="btnradio2"
        id="get_sent_requests_btn">Sent Requests ({{ $sentRequestsCount }})</a>

    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
        @checked(request()->routeIs('connections.received.requests'))>
    <a href="{{ route('connections.received.requests') }}" class="btn btn-outline-primary" for="btnradio3"
        id="get_connections_btn">
        Received Requests({{ $receivedRequestsCount }})
    </a>

    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
        @checked(request()->routeIs('connections.index'))>
    <a href="{{ route('connections.index') }}" class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">
        Connections ({{ $connectionsCount }})
    </a>
</div>
