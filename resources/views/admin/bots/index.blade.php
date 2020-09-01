@extends('layouts.admin')
@push('css')
    <style>
        .list-group {
            max-height: 300px;
            margin-bottom: 10px;
            overflow: scroll;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="panel panel-primary col-md-8">
                <div class="panel-heading"><h4 class="panel-title">Боты</h4></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-sm text-center">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Вкл/Выкл</th>
                                <th scope="col">Время для ставки</th>
                                <th scope="col">Менять имя</th>
                                <th scope="col">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{$botOne->number}}</th>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-toggle @if($botOne->is_active) active @endif"
                                            data-toggle="button" @if($botOne->is_active) aria-pressed="true"
                                            @else aria-pressed="false" @endif  autocomplete="off"
                                            onclick='oNoFF("{{route('admin.bots.update',[$botOne->id])}}",{is_active:($(this).attr("aria-pressed") === "true" ? 0 : 1),},"PUT")'>
                                        <span class="handle"></span>
                                    </button>
                                </td>
                                <td>{{$botOne->time_to_bet}}</td>
                                <td>{{$botOne->change_name}}</td>
                                <td>
                                    <a href="{{route('admin.bots.edit',$botOne->id)}}" class="btn btn-sm btn-info">изменить</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm text-center">
                            <thead class="thead-light nowrap">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Вкл/Выкл</th>
                                <th scope="col">Время для ставки</th>
                                <th scope="col">Количество ходов</th>
                                <th scope="col">Количество ходов с другим ботом</th>
                                <th scope="col">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bots as $bot)
                                <tr>
                                    <th scope="row">{{$bot->number}}</th>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-toggle @if($bot->is_active) active @endif"
                                                data-toggle="button" @if($bot->is_active) aria-pressed="true"
                                                @else aria-pressed="false" @endif  autocomplete="off"
                                                onclick='oNoFF("{{route('admin.bots.update',[$bot->id])}}",{is_active:($(this).attr("aria-pressed") === "true" ? 0 : 1),},"PUT")'>
                                            <span class="handle"></span>
                                        </button>
                                    </td>
                                    <td>{{$bot->time_to_bet}}</td>
                                    <td>{{$bot->num_moves}}</td>
                                    <td>{{$bot->num_moves_other_bot}}</td>
                                    <td>
                                        <a href="{{route('admin.bots.edit',$bot->id)}}" class="btn btn-sm btn-info">изменить</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary col-md-4" id="result_panel">
                <div class="panel-heading"><h4 class="panel-title">Список имен ботов </h4></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item text-right">
                            <a href="{{route('admin.bots.create')}}" class="btn btn-sm btn-success">Добавить</a>
                        </li>
                        @foreach($names as $name)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-9 text-left">{{$name->name}}</div>
                                    <div class="col-md-3 text-right">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#resourceModal"
                                                    data-action="{{route('admin.bots.destroy.name',$name->id)}}">
                                                удалить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
