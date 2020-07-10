@extends('layouts.admin')
@section('content')
    <div class="modal fade" tabindex="1" id="cardModal" role="dialog" aria-labelledby="cardModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="cardModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

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
        <h1 class="h3 mb-2 text-gray-800">Пользователи</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="m-0 font-weight-bold text-primary">Всего товаров в базе: <span
                                class="text-dark">{{$productsInfo['product_count']}}</span></span>
                    </div>
                    <div class="col-md-4">
                        <span class="m-0 font-weight-bold text-primary">Выводит на сайте: <span
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

@endsection
