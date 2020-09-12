@extends('layouts.admin')
@section('content')
    <div class="modal fade" tabindex="1" id="cardModal" role="dialog" aria-labelledby="cardModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="cardModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыт</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Аукционы </h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row text-center">
                    <div class="col-md-6">
                        <span class="m-0 font-weight-bold text-primary">Всего аукционов в базе: <span
                                class="text-dark">{{$auctionsInfo['auction_count']}}</span></span>
                    </div>
                    <div class="col-md-6">
                        <span class="m-0 font-weight-bold text-primary">На сайте: <span
                                id="visibly_count"
                                class="text-dark">{{$auctionsInfo['active_count']}}</span></span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped nowrap" width="100%" cellspacing="0" align="center"
                           id="product_table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Фото</th>
                            <th>Наименование</th>
                            <th>Статус</th>
                            <th>Ставок</th>
                            <th>Бонусов</th>
                            <th>Бот</th>
                            <th>Старт</th>
                            <th>Финиш</th>
                            <th>На сайте</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(function () {
                $('#product_table').DataTable({
                    aLengthMenu: [
                        [100, -1],
                        [100,"Все"]
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.dashboard') }}',
                    "language": {
                        "url": "{{url('/datatables/lang/Russian.json')}}"
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'img_1', name: 'img_1'},
                        {data: 'title', name: 'title'},
                        {data: 'status', name: 'status'},
                        {data: 'bet', name: 'bet'},
                        {data: 'bonus', name: 'bonus'},
                        {data: 'bot', name: 'bot'},
                        {data: 'start', name: 'start'},
                        {data: 'end', name: 'end'},
                        {data: 'active', name: 'active'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            });
            $(function () {
                $(document).on('click', '[data-target="#cardModal"]', function (e) {
                    let url = $(this).data('href');
                    let method = 'GET';
                    let data = null;
                    let modal = $($(this).data('target'));
                    modal.find('.modal-body').html('');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: method,
                        url: url,
                        data: data,
                        success: (data) => {
                            if (data.success && data.html) {
                                modal.find('.modal-body').html(data.html);
                                modal.find('#cardModalLabel').text(data.title);
                                $('#auction_card').DataTable({
                                    paging: false,
                                    "language": {
                                        "url": "{{url('/datatables/lang/Russian.json')}}"
                                    },
                                })
                            }
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                })
            })
        </script>
    @endpush
@endsection
