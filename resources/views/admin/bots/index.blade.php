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
                    ddd
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
