@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Каталог</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="m-0 font-weight-bold text-primary">Всего товаров в базе: <span
                                class="text-dark">{{$productsInfo['product_count']}}</span></span>
                    </div>
                    <div class="col-md-4">
                        <span class="m-0 font-weight-bold text-primary">Выводит на сайте: <span
                                id="visibly_count"
                                class="text-dark">{{$productsInfo['visibly_count']}}</span></span>
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-sm btn-success float-right" href="{{route('admin.products.create')}}">Добавить</a>
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
                            <th>Производитель</th>
                            <th>Группа товара</th>
                            <th>Стартовая цена (руб)</th>
                            <th>Полная цена (руб)</th>
                            <th>Отключения бота (руб)</th>
                            <th>Таймер (сек)</th>
                            <th>Шаг (кол)</th>
                            <th>Автозапуск (мин)</th>
                            <th>Обмен на ставки</th>
                            <th>Купить сейчас</th>
                            <th>Топ</th>
                            <th>Выводит на сайт</th>
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
                    ajax: '{{ route('admin.products.index') }}',
                    "language": {
                        "url": "{{url('/datatables/lang/Russian.json')}}"
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'img_1', name: 'img_1'},
                        {data: 'title', name: 'title'},
                        {data: 'company', name: 'company'},
                        {data: 'category', name: 'category'},
                        {data: 'start_price', name: 'start_price'},
                        {data: 'full_price', name: 'full_price'},
                        {data: 'bot_shutdown_price', name: 'bot_shutdown_price'},
                        {data: 'step_time', name: 'step_time'},
                        {data: 'step_price', name: 'step_price'},
                        {data: 'to_start', name: 'to_start'},
                        {data: 'exchange', name: 'exchange'},
                        {data: 'buy_now', name: 'buy_now'},
                        {data: 'top', name: 'top'},
                        {data: 'visibly', name: 'visibly'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
@endsection
