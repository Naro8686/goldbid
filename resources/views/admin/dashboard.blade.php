@extends('layouts.admin')
@section('content')
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
        </script>
    @endpush
@endsection
