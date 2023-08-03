<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                        @checked(request()->routeIs('connections.suggestions') || request()->routeIs('home.*'))>
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
                    <a href="{{ route('connections.received.requests') }}" class="btn btn-outline-primary"
                        for="btnradio3" id="get_connections_btn">
                        Received Requests({{ $receivedRequestsCount }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
                        @checked(request()->routeIs('connections.index'))>
                    <a href="{{ route('connections.index') }}" class="btn btn-outline-primary" for="btnradio4"
                        id="get_connections_btn">
                        Connections ({{ $connectionsCount }})
                    </a>
                </div>
                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>

                <span class="fw-bold">Connections </span>
                <div id="connections_div">
                    @forelse ($connections as $connection)
                        @if (auth()->user()->id != $connection->user_id)
                            <div class="my-2 shadow text-white bg-dark p-1">
                                <div class="d-flex justify-content-between">
                                    <table class="ms-1">
                                        <td class="align-middle">{{ $connection->user->name }}</td>
                                        <td class="align-middle"> - </td>
                                        <td class="align-middle">{{ $connection->user->email }}</td>
                                        <td class="align-middle">
                                    </table>
                                    <div>
                                        <button style="width: 220px" id="get_connections_in_common_connetctions"
                                            class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_{{ $connection->id }}" aria-expanded="false"
                                            aria-controls="collapseExample">
                                            Connections in common ({{ $connection->commonConnections->total() }})
                                        </button>
                                        <button id="create_request_btn_"
                                            onclick="removeConn('{{ $connection->id }}','connection')"
                                            class="btn btn-danger me-1">Remove Connection</button>
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_{{ $connection->id }}">

                                    <div id="content_{{ $connection->id }}" class="p-2">
                                        {{-- Display data here --}}
                                        <x-connection_in_common :commonConnections="$connection->commonConnections" />
                                    </div>

                                    @if ($connection->commonConnections->lastPage() != $connection->commonConnections->currentPage())
                                        {{-- common connections load more and skeleton --}}
                                        <div id="connections_in_common_skeletons_{{ $connection->id }}" class="d-none">
                                            @for ($i = 0; $i < 10; $i++)
                                                <x-skeleton />
                                            @endfor
                                        </div>

                                        <div class="d-flex justify-content-center mt-2 py-3"
                                            id="load_more_{{ $connection->id }}">
                                            <button class="btn btn-primary"
                                                onclick="loadMoreCommon('{{ $connection->user->id }}','{{ $connection->id }}')"
                                                id="load_more_btn_common_connections">
                                                Load more
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="my-2 shadow text-white bg-dark p-1">
                                <div class="d-flex justify-content-between">
                                    <table class="ms-1">
                                        <td class="align-middle">{{ $connection->connectedUser->name }}</td>
                                        <td class="align-middle"> - </td>
                                        <td class="align-middle">{{ $connection->connectedUser->email }}</td>
                                        <td class="align-middle">
                                    </table>
                                    <div>
                                        <button style="width: 220px" id="get_connections_in_common_connetctions"
                                            class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_{{ $connection->id }}" aria-expanded="false"
                                            aria-controls="collapseExample">
                                            Connections in common ({{ $connection->commonConnections->total() }})
                                        </button>
                                        <button id="create_request_btn_"
                                            onclick="removeConn('{{ $connection->id }}','connection')"
                                            class="btn btn-danger me-1">Remove Connection</button>
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_{{ $connection->id }}">

                                    <div id="content_{{ $connection->id }}" class="p-2">
                                        {{-- Display data here --}}
                                        <x-connection_in_common :commonConnections="$connection->commonConnections" />
                                    </div>

                                    @if ($connection->commonConnections->lastPage() != $connection->commonConnections->currentPage())
                                        {{-- common connections load more and skeleton --}}
                                        <div id="connections_in_common_skeletons_{{ $connection->id }}"
                                            class="d-none">
                                            @for ($i = 0; $i < 10; $i++)
                                                <x-skeleton />
                                            @endfor
                                        </div>

                                        <div class="d-flex justify-content-center mt-2 py-3"
                                            id="load_more_{{ $connection->id }}">
                                            <button class="btn btn-primary"
                                                onclick="loadMoreCommon('{{ $connection->connectedUser->id }}','{{ $connection->id }}')"
                                                id="load_more_btn_common_connections">
                                                Load more
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @empty
                        <p>No Sent Requests Found.</p>
                    @endforelse
                </div>

                {{-- connections load more and skeleton --}}
                @if ($connections->currentPage() != $connections->lastPage())
                    <div id="connections_skeleton" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>

                    <x-load_more type="connections" />
                @endif

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // function to send request for adding connection
        function removeConn(id) {
            var requestUrl = "{{ route('connections.destroy', ':id') }}";
            requestUrl = requestUrl.replace(':id', id);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // AJAX request to remove the connection
            $.ajax({
                url: requestUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                dataType: 'json',
                data: {
                    "id": id
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        // load more connections data ajax call
        var page_no = 1;
        var flag = true;
        var type = "connections";

        function loadMore() {
            if (flag) {
                $("#connections_skeleton").removeClass('d-none');
                $.ajax({
                    url: "{{ route('connections.index') }}",
                    type: 'GET',
                    data: {
                        "page": page_no + 1,
                    },
                    success: function(response) {
                        $("#connections_skeleton").addClass('d-none');
                        var element = '';
                        if (response.data.length) {
                            $.each(response.data, function(key, val) {
                                if ("{{ auth()->user()->id }}" != val.connected_user.id) {
                                    element = element + `
                                        <div class="my-2 shadow text-white bg-dark p-1">
                                            <div class="d-flex justify-content-between">
                                                <table class="ms-1">
                                                    <td class="align-middle">${val.connected_user.name}</td>
                                                    <td class="align-middle"> - </td>
                                                    <td class="align-middle">${val.connected_user.email}</td>
                                                    <td class="align-middle">
                                                </table>
                                                <div>
                                                    <button style="width: 220px" id="get_connections_in_common_connetctions"
                                                        class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse_${val.id}" aria-expanded="false"
                                                        aria-controls="collapseExample">
                                                        Connections in common (${val.commonConnections.data.length})
                                                    </button>
                                                    <button id="create_request_btn_"
                                                        onclick="removeConn('${val.id}','connection')"
                                                        class="btn btn-danger me-1">Remove Connection</button>
                                                </div>
                                            </div>
                                            <div class="collapse" id="collapse_${val.id}"> </div>
                                        </div>`
                                } else {
                                    element = element + `
                                        <div class="my-2 shadow text-white bg-dark p-1">
                                            <div class="d-flex justify-content-between">
                                                <table class="ms-1">
                                                    <td class="align-middle">${val.user.name}</td>
                                                    <td class="align-middle"> - </td>
                                                    <td class="align-middle">${val.user.email}</td>
                                                    <td class="align-middle">
                                                </table>
                                                <div>
                                                    <button style="width: 220px" id="get_connections_in_common_connetctions"
                                                        class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse_${val.id}" aria-expanded="false"
                                                        aria-controls="collapseExample">
                                                        Connections in common (${val.commonConnections.data.length})
                                                    </button>
                                                    <button id="create_request_btn_"
                                                        onclick="removeConn('${val.id}','connection')"
                                                        class="btn btn-danger me-1">Remove Connection</button>
                                                </div>
                                            </div>
                                            <div class="collapse" id="collapse_${val.id}"> </div>
                                        </div>`
                                }
                            });
                        }
                        $('#connections_div').append(element);

                        page_no = page_no + 1;
                        console.log(page_no);

                        if (response.last_page == page_no) {
                            flag = false;
                            $('#load_more_btn_parent_connections').addClass('d-none');
                        }
                    },
                    error: function(textStatus, errorThrown) {
                        $("#connections_skeleton").addClass('d-none');
                    }
                });
            }

        }

        var page_numbers = [];
        var have_more = [];

        function loadMoreCommon(user_id, id) {
            if (page_numbers[id] == undefined) {
                page_numbers[id] = 1;
                have_more[id] = true;
            }
            if (have_more[id]) {
                $('#connections_in_common_skeletons_' + id).removeClass('d-none');
                $.ajax({
                    url: "{{ route('connections.common') }}",
                    type: 'GET',
                    data: {
                        "page": page_numbers[id] + 1,
                        "id": user_id,
                    },
                    success: function(res) {
                        $('#connections_in_common_skeletons_' + id).addClass('d-none');
                        console.log(res.data);

                        var e = '';
                        if (res.data.length) {
                            $.each(res.data, function(key, val) {
                                e = e + '<div class="p-2 shadow rounded mt-2  text-white bg-dark">' +
                                    val.name + ' - ' + val.email + '</div>';
                            });
                        }
                        $('#content_' + id).append(e);
                        page_numbers[id] = page_numbers[id] + 1;
                        if (res.last_page == page_numbers[id]) {
                            have_more[id] = false;
                            $('#load_more_' + id).addClass('d-none');
                        }
                    },
                    error: function(textStatus, errorThrown) {
                        $('#connections_in_common_skeletons_' + id).addClass('d-none');
                    }
                });
            }

        }
    </script>
@endpush
