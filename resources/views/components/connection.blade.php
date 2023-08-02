<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                        @checked(request()->routeIs('suggestions.*') || request()->routeIs('home.*'))>
                    <a href="{{ route('suggestions.index') }}" class="btn btn-outline-primary" for="btnradio1"
                        id="get_suggestions_btn">
                        Suggestions ({{ $suggestionsCount }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                        @checked(request()->routeIs('sent-requests.*'))>
                    <a href="{{ route('sent-requests.index') }}" class="btn btn-outline-primary" for="btnradio2"
                        id="get_sent_requests_btn">Sent Requests ({{ $sentRequestsCount }})</a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
                        @checked(request()->routeIs('received-requests.*'))>
                    <a href="{{ route('received-requests.index') }}" class="btn btn-outline-primary" for="btnradio3"
                        id="get_connections_btn">
                        Received Requests({{ $receivedRequestsCount }})
                    </a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
                        @checked(request()->routeIs('connections.*'))>
                    <a href="{{ route('connections.index') }}" class="btn btn-outline-primary" for="btnradio4"
                        id="get_connections_btn">
                        Connections ({{ $connectionsCount }})
                    </a>
                </div>
                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>

                <span class="fw-bold">Received Requests </span>
                <div id="connections_div">
                    @forelse ($connections as $connection)
                        <div class="my-2 shadow text-white bg-dark p-1">
                            <div class="d-flex justify-content-between">
                                <table class="ms-1">
                                    <td class="align-middle">{{ $connection->connectedUser->name ?? 'N/A' }}</td>
                                    <td class="align-middle"> - </td>
                                    <td class="align-middle">{{ $connection->connectedUser->email ?? 'N/A' }}</td>
                                    <td class="align-middle">
                                </table>
                                <div>
                                    <button style="width: 220px" id="get_connections_in_common_remove_connection"
                                        class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        Connections in common ()
                                    </button>
                                    <button onclick="removeConn({{ $connection->id }});"
                                        id="create_request_btn_remove_connection" class="btn btn-danger me-1">
                                        Remove Connection
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No Sent Requests Found.</p>
                    @endforelse
                </div>

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
                                element = element + `<div class="my-2 shadow text-white bg-dark p-1">
                            <div class="d-flex justify-content-between">
                                <table class="ms-1">
                                    <td class="align-middle">${val.connected_user.name ?? 'N/A'}</td>
                                    <td class="align-middle"> - </td>
                                    <td class="align-middle">${val.connected_user.email ?? 'N/A'}</td>
                                    <td class="align-middle">
                                </table>
                                <div>
                                    <button style="width: 220px" id="get_connections_in_common_remove_connection"
                                        class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        Connections in common ()
                                    </button>
                                    <button onclick="removeConn(${val.id});"
                                        id="create_request_btn_remove_connection" class="btn btn-danger me-1">
                                        Remove Connection
                                    </button>
                                </div>
                            </div>
                        </div>`;
                            });
                        }
                        $('#connections_div').append(element);

                        page_no = page_no + 1;

                        if (response.last_page == page_no) {
                            flag = false;
                            $('#load_more_btn_parent_' + type).addClass('d-none');
                        }
                    },
                    error: function(textStatus, errorThrown) {
                        $("#connections_skeleton").addClass('d-none');
                    }
                });
            }

        }
    </script>
@endpush
