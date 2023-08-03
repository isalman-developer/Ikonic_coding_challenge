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

                <span class="fw-bold">Suggestions</span>
                <div id="suggestions_div">
                    @forelse ($suggestions as $suggestion)
                        <div class="my-2 shadow  text-white bg-dark p-1">
                            <div class="d-flex justify-content-between">
                                {{-- table component showing name and email --}}
                                <x-table :name="$suggestion->name" :email="$suggestion->email" />
                                <div>
                                    <button onclick="addConnection({{ $suggestion->id }});"
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

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // function to send request for adding connection
        function addConnection(userId) {
            var requestUrl = "{{ route('connection-requests.store') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // AJAX request to send the connection request
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

        // load more suggestions connection by using ajax call
        var page_no = 1;
        var flag = true;
        var type = "suggestions";

        function loadMore() {
            if (flag) {
                $("#suggestion_skeleton").removeClass('d-none');
                $.ajax({
                    url: "{{ route('connections.suggestions') }}",
                    type: 'GET',
                    data: {
                        "page": page_no + 1,
                    },
                    success: function(res) {
                        $("#suggestion_skeleton").addClass('d-none');

                        var e = '';
                        if (res.data.length) {
                            // iterating over data and assigning it to the div

                            $.each(res.data, function(key, val) {
                                e = e +
                                    `<div class="my-2 shadow  text-white bg-dark p-1"><div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle"> ${val.name}  </td><td class="align-middle"> - </td><td class="align-middle"> ${val.email} </td><td class="align-middle"></div> </table><div><button onclick=addConnection(${val.id}) id="create_request_btn_" class="btn btn-primary me-1">Connect</button></div></div></div>`;
                            });
                        }
                        $('#suggestions_div').append(e);

                        // checking page number and then disabled load more if its the last page
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
