@extends('layouts.admin')
@section('content')
    <div id="dynamic_link" class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Главная страница </h1>
        <div id="social" class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Slider</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-center" width="100%" cellspacing="0" align="center">
                        <thead>
                        <tr>
                            <th>Картинка </th>
                            <th>Alt</th>
                            <th>Наименование</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4">
                                <button type="button" class="btn btn-sm btn-success float-right disabled" data-toggle="modal" data-target="#exampleModal" data-type="insert" data-social="true">добавить</button>
                            </th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td><img class="img-fluid img-thumbnail" src="{{asset($slider->image)}}" alt="{{$slider->alt}}"
                                         width="50"></td>
                                <td>
                                    <p class="name">{{$slider->alt}}</p>
                                </td>
                                <td>
                                    <p>
                                        {{$slider->text}}
                                    </p>
                                </td>

                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-info disabled" data-toggle="modal" data-target="#exampleModal" data-type="update" data-id="{{$slider->id}}" data-social="true">изменить</button>
                                        <button type="button" class="btn btn-danger disabled" data-toggle="modal" data-target="#exampleModal" data-type="delete" data-id="{{$slider->id}}">удалить</button>
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
