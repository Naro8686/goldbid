@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Рассылки</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Сервисные рассылки</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" width="100%" cellspacing="0" align="center">
                        <thead>
                        <tr>
                            <th>Тема</th>
                            <th>Текст</th>
                            <th>Вкл./Выкл.</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mailings['no_ads'] as $mailing)
                            <tr>
                                <td>
                                    <p class="name">{{$mailing->subject}}</p>
                                </td>
                                <td>
                                    <p class="name">{{$mailing->text}}</p>
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-toggle @if($mailing->visibly) active @endif"
                                            data-toggle="button" @if($mailing->visibly) aria-pressed="true"
                                            @else aria-pressed="false" @endif  autocomplete="off"
                                            onclick='oNoFF("{{route('admin.mailings.update',[$mailing->id])}}",{visibly:($(this).attr("aria-pressed") === "true" ? 0 : 1),},"PUT")'>
                                        <span class="handle"></span>
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('admin.mailings.edit',[$mailing->id])}}" class="btn btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                              <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span class="text">изменить</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Рекламные рассылки</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" width="100%" cellspacing="0" align="center">
                        <thead>
                        <tr>
                            <th>Наименование</th>
                            <th>Тема</th>
                            <th>Текст</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4">
                                <a href="{{route('admin.mailings.create')}}" class="btn btn-success btn-icon-split float-right">
                                            <span class="icon text-white-50">
                                              <i class="fas fa-check"></i>
                                            </span>
                                    <span class="text">добавить</span>
                                </a>
                            </th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($mailings['ads'] as $mailing)
                            <tr>
                                <td>
                                    <p class="name">{{$mailing->title}}</p>
                                </td>
                                <td>
                                    <p class="name">{{$mailing->subject}}</p>
                                </td>
                                <td>
                                    <p class="name">{{$mailing->text}}</p>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('admin.mailings.edit',[$mailing->id])}}" class="btn btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                              <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span class="text">изменить</span>
                                        </a>
                                        <button class="btn btn-danger btn-icon-split"
                                                type="button"
                                                data-toggle="modal"
                                                data-target="#resourceModal"
                                                data-action="{{route('admin.mailings.destroy',[$mailing->id])}}">
                                            <span class="icon text-white-50">
                                              <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">удалить</span>
                                        </button>
                                        <a href="{{route('admin.mailings.send',[$mailing->id])}}" class="btn btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                              <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Отправить</span>
                                        </a>
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
@endsection
