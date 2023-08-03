<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow  text-white bg-dark">
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
                    <a href="{{ route('connections.received.requests') }}" class="btn btn-outline-primary" for="btnradio3"
                        id="get_received_requests_btn">
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

                <span class="fw-bold">Received Requests </span>
                <div id="received_requests_div">
                    @forelse ($receivedRequests as $receivedRequest)
                        <div class="my-2 shadow text-white bg-dark p-1">
                            <div class="d-flex justify-content-between">
                                <table class="ms-1">
                                    <td class="align-middle">{{ $receivedRequest->sender->name ?? 'N/A' }}</td>
                                    <td class="align-middle"> - </td>
                                    <td class="align-middle">{{ $receivedRequest->sender->email ?? 'N/A' }}</td>
                                    <td class="align-middle">
                                </table>
                                <div>
                                    <button onclick="acceptReceivedRequest('{{ $receivedRequest->id }}');"
                                        id="withdraw_btn_received_requests" class="btn btn-primary">
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No Received Requests Found.</p>
                    @endforelse
                </div>

                @if ($receivedRequests->currentPage() != $receivedRequests->lastPage())
                    <div id="received_requests_skeleton" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>

                    <x-load_more type="received_requests" />
                @endif

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // function to send request for adding connection
        function acceptReceivedRequest(id) {
            var requestUrl = "{{ route('connections.store') }}";
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
                    "id": id
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

        // load more received_requests data ajax call
        var page_no = 1;
        var flag = true;
        var type = "received_requests";

        function loadMore() {

            if (flag) {
                $("#received_requests_skeleton").removeClass('d-none');
                $.ajax({
                    url: "{{ route('connections.received.requests') }}",
                    type: 'GET',
                    data: {
                        "page": page_no + 1,
                    },
                    success: function(response) {
                        $("#received_requests_skeleton").addClass('d-none');
                        var element = '';
                        if (response.data.length) {
                            $.each(response.data, function(key, val) {

                                element = element + `<div class="my-2 shadow text-white bg-dark p-1">
                                            <div class="d-flex justify-content-between">
                                                <table class="ms-1">
                                                    <td class="align-middle">${val.sender.name}</td>
                                                    <td class="align-middle"> - </td>
                                                    <td class="align-middle">${val.sender.email}</td>
                                                    <td class="align-middle">
                                                </table>
                                                <div>
                                                    <button onclick="acceptReceivedRequest(${val.id});"
                                                        id="withdraw_btn_received_requests" class="btn btn-primary">
                                                        Accept
                                                    </button>
                                                </div>
                                            </div>
                                        </div>`;
                            });
                        }
                        $('#received_requests_div').append(element);

                        page_no = page_no + 1;

                        if (response.last_page == page_no) {
                            flag = false;
                            $('#load_more_btn_parent_' + type).addClass('d-none');
                        }
                    },
                    error: function(textStatus, errorThrown) {
                        $("#received_requests_skeleton").addClass('d-none');
                    }
                });
            }

        }
    </script>
@endpush
