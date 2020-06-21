@extends('layouts.admin')
@section('content')
    @if(isset($meta))
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Пополнить баланс</h1>
            @include('admin.includes.pages.seo_update')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Пакет ставок</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%" cellspacing="0" align="center">
                            <thead>
                            <tr>
                                <th>Картинка</th>
                                <th>Ставок</th>
                                <th>Бонусов</th>
                                <th>Цена</th>
                                <th>Вкл/Выкл</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th colspan="6">
                                    <a href="{{route('admin.packages.create')}}"
                                       class="btn btn-sm btn-success float-right">добавить</a>
                                </th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td>
                                        <img class="img-fluid img-thumbnail" src="{{asset($package->image)}}"
                                             alt="{{$package->alt}}"
                                             width="100">
                                    </td>
                                    <td>
                                        <p class="name">{{$package->bet}}</p>
                                    </td>
                                    <td>
                                        <p class="name">{{$package->bonus}}</p>
                                    </td>
                                    <td>
                                        <p class="name">{{$package->price}}</p>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-toggle @if($package->visibly) active @endif"
                                                data-toggle="button" @if($package->visibly) aria-pressed="true"
                                                @else aria-pressed="false" @endif  autocomplete="off"
                                                onclick='oNoFF(this,"{{route('admin.packages.update',[$package->id])}}",{},"PUT")'>
                                            <span class="handle"></span>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <a href="{{route('admin.packages.edit',[$package->id])}}"
                                               class="btn btn-info">изменить</a>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#resourceModal"
                                                    data-action="{{route('admin.packages.destroy',[$package->id])}}">
                                                удалить
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
