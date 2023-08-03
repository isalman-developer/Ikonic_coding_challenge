<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow  text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                {{-- menu component to show tabs --}}
                <x-menu :suggestionsCount="$suggestionsCount" :sentRequestsCount="$sentRequestsCount" :receivedRequestsCount="$receivedRequestsCount" :connectionsCount="$connectionsCount" />

                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>

                <span class="fw-bold">Sent Requests</span>
                <div id="sent_requests_div">
                    @forelse ($sentRequests as $sentRequest)
                        <div class="my-2 shadow text-white bg-dark p-1" id="sent_request_div_{{ $sentRequest->id }}">
                            <div class="d-flex justify-content-between">
                                {{-- table component showing name and email --}}
                                <x-table :name="$sentRequest->receiver->name" :email="$sentRequest->receiver->email" />
                                <div>
                                    <button onclick="withDrawConnection({{ $sentRequest->id }});"
                                        id="withdraw_btn_sent_requests" class="btn btn-danger">
                                        Withdraw Request
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No Sent Requests Found.</p>
                    @endforelse
                </div>

                @if ($sentRequests->currentPage() != $sentRequests->lastPage())
                    <div id="sent_requests_skeleton" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>

                    <x-load_more type="sent_requests" />
                @endif

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // function to send request for adding connection
        function withDrawConnection(id) {
            var requestUrl = "{{ route('connection-requests.destroy', ':id') }}";
	        requestUrl = requestUrl.replace(':id', id);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // AJAX request to send the connection request using connects url
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
                beforeSend: function() {
                    $(`#sent_request_div_${id}`).html(`
                    <div class="d-flex align-items-center  mb-2  text-white bg-dark p-1 shadow" style="height: 45px">
                        <strong class="ms-1 text-primary">Loading...</strong>
                        <div class="spinner-border ms-auto text-primary me-4" role="status" aria-hidden="true"></div>
                    </div>
                    `);
                },
                success: function(response) {
                    $(`#sent_request_div_${id}`).remove();

                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        // load more sent_requests data ajax call
        var page_no = 1;
        var flag = true;
        var type = "sent_requests";

        function loadMore() {

            if (flag) {
                $("#sent_requests_skeleton").removeClass('d-none');
                $.ajax({
                    url: "{{ route('connections.sent.requests') }}",
                    type: 'GET',
                    data: {
                        "page": page_no + 1,
                    },
                    success: function(res) {
                        $("#sent_requests_skeleton").addClass('d-none');
                        var e = '';
                        if (res.data.length) {
                            $.each(res.data, function(key, val) {
                                e = e + `<div class="my-2 shadow text-white bg-dark p-1" id="sent_request_div_${val.id}"> <div id="${type}+'_'+${val.id}" class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle">${val.receiver.name}</td><td class="align-middle"> - </td><td class="align-middle">${val.receiver.email}</td><td class="align-middle"></div> </table><div><button id="cancel_request_btn_" class="btn btn-danger me-1" onclick=withDrawConnection(${val.id})>Withdraw Request</button></div></div></div>`;
                            });
                        }
                        $('#sent_requests_div').append(e);
                        page_no = page_no + 1;
                        if (res.last_page == page_no) {
                            flag = false;
                            $('#load_more_btn_parent_' + type).addClass('d-none');
                        }
                    },
                    error: function(textStatus, errorThrown) {
                        $("#sent_requests_skeleton").addClass('d-none');
                    }
                });
            }

        }
    </script>
@endpush
