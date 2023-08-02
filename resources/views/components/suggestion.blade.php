<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow  text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                        @checked(request()->routeIs('suggestions.*') || request()->routeIs('home.*'))>
                    <a href="{{ route('suggestions.index') }}" class="btn btn-outline-primary"
                        for="btnradio1" id="get_suggestions_btn">
                        Suggestions ({{ $suggestionsCount }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                        @checked(request()->routeIs('sent-connections.*'))>
                    <a href="{{ route('sent-connections.index') }}" class="btn btn-outline-primary"
                        for="btnradio2" id="get_sent_requests_btn">Sent Requests ()</a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
                        @checked(request()->routeIs('received-connections.*'))>
                    <a href="{{ route('received-connections.index') }}" class="btn btn-outline-primary"
                        for="btnradio3" id="get_received_requests_btn">
                        Received Requests({{ $receivedRequestsCount }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
                        @checked(request()->routeIs('connections.*'))>
                    <a href="{{ route('connections.index') }}" class="btn btn-outline-primary"
                        for="btnradio4" id="get_connections_btn">
                        Connections ({{ $connectionsCount }})
                    </a>
                </div>
                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>

                @if (request()->input('type') == 'suggestions' || request()->input('type') == '')
                    <span class="fw-bold">Suggestions</span>
                    <div id="suggestions_div">
                        @forelse ($suggestions as $suggestion)
                            <div class="my-2 shadow  text-white bg-dark p-1">
                                <div class="d-flex justify-content-between">
                                    <table class="ms-1">
                                        <td class="align-middle">{{ $suggestion->name ?? 'N/A' }}</td>
                                        <td class="align-middle"> - </td>
                                        <td class="align-middle">{{ $suggestion->email ?? 'N/A' }}</td>
                                        <td class="align-middle">
                                    </table>
                                    <div>
                                        <button onclick="addConnection('{{ $suggestion->id }}');"
                                            id="create_request_btn_suggestion" class="btn btn-primary me-1">
                                            Connect
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>No Suggestions Found.</p>
                        @endforelse
                    </div>

                    @if ($suggestions->currentPage() != $suggestions->lastPage())
                        <div id="suggestion_skeleton" class="d-none">
                            @for ($i = 0; $i < 10; $i++)
                                <x-skeleton />
                            @endfor
                        </div>

                        <x-load_more type="suggestions" />
                    @endif
                @else
                @endif

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // function to send request for adding connection
        function addConnection(userId) {
            var requestUrl = '{{ route('connection-requests.store') }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // AJAX request to send the connection request using connects url
            $.ajax({
                url: requestUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                dataType: 'json',
                data: {
                    "id": userId
                },
                success: function(response) {
                    // on success reload the page
                    window.location.reload();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        // load more suggestions data ajax call
        var page_no = 1;
        var flag = true;
        var type = "suggestions";

        function loadMore() {

            if (flag) {
                $("#suggestion_skeleton").removeClass('d-none');
                $.ajax({
                    url: "{{ route('suggestions.index') }}",
                    type: 'GET',
                    data: {
                        "page": page_no + 1,
                    },
                    success: function(res) {
                        $("#suggestion_skeleton").addClass('d-none');

                        var e = '';
                        if (res.data.length) {
                            $.each(res.data, function(key, val) {
                                e = e +
                                    `<div class="my-2 shadow  text-white bg-dark p-1"><div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle"> ${val.name}  </td><td class="align-middle"> - </td><td class="align-middle"> ${val.email} </td><td class="align-middle"></div> </table><div><button onclick=addConnection(${val.id}) id="create_request_btn_" class="btn btn-primary me-1">Connect</button></div></div></div>`;
                            });
                        }
                        $('#suggestions_div').append(e);
                        page_no = page_no + 1;
                        if (res.last_page == page_no) {
                            flag = false;
                            $('#load_more_btn_parent_' + type).addClass('d-none');
                        }
                    },
                    error: function(textStatus, errorThrown) {
                        $("#suggestion_skeleton").addClass('d-none');
                    }
                });
            }

        }
    </script>
@endpush
