<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow  text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                        @checked(request()->input('type') == 'suggestions' || request()->input('type') == '')>
                    <a href="{{ route('home', ['type' => 'suggestions']) }}" class="btn btn-outline-primary"
                        for="btnradio1" id="get_suggestions_btn">
                        Suggestions ({{ $suggestions->count() }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                        @checked(request()->input('type') == 'sentRequests')>
                    <a href="{{ route('home', ['type' => 'sentRequests']) }}" class="btn btn-outline-primary"
                        for="btnradio2" id="get_sent_requests_btn">Sent Requests ()</a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
                        @checked(request()->input('type') == 'receivedRequests')>
                    <a href="{{ route('home', ['type' => 'receivedRequests']) }}" class="btn btn-outline-primary"
                        for="btnradio3" id="get_received_requests_btn">
                        Received Requests({{ $receivedRequests->count() }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
                        @checked(request()->input('type') == 'connections')>
                    <a href="{{ route('home', ['type' => 'connections']) }}" class="btn btn-outline-primary"
                        for="btnradio4" id="get_connections_btn">
                        Connections ({{ $connections->count() }})
                    </a>
                </div>
                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>

                {{-- Remove this when you start working, just to show you the different components --}}
                {{-- <span class="fw-bold">Sent Request Blade</span>
                <x-request :mode="'sent'" />

                <span class="fw-bold">Received Request Blade</span>
                <x-request :mode="'received'" /> --}}

                @if (request()->input('type') == 'suggestions' || request()->input('type') == '')
                    <span class="fw-bold">Suggestions</span>
                    @forelse ($suggestions as $suggestion)
                        <x-suggestion :suggestion="$suggestion" />
                    @empty
                        <p>No Suggestions Found.</p>
                    @endforelse
                    @if ($suggestions->currentPage() != $suggestions->lastPage())
                        <div id="skeleton" class="d-none">
                            @for ($i = 0; $i < 10; $i++)
                                <x-skeleton />
                            @endfor
                        </div>
                        <x-load_more />
                    @endif
                @else
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Remove this when you start working, just to show you the different components --}}

<div id="connections_in_common_skeleton" class="d-none">
    <br>
    <span class="fw-bold text-white">Loading Skeletons</span>
    <div class="px-2">
        @for ($i = 0; $i < 10; $i++)
            <x-skeleton />
        @endfor
    </div>
</div>
