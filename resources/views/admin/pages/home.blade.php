@extends('layouts.admin')
@section('content')

    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Главная страница </h1>
        @include('admin.includes.pages.seo_update')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Slider</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-center" width="100%" cellspacing="0" align="center">
                        <thead>
                        <tr>
                            <th>Картинка</th>
                            <th>Alt</th>
                            <th>Наименование</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4">
                                <a href="{{route('admin.sliders.create')}}" class="btn btn-sm btn-success float-right">добавить</a>
                            </th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td><img class="img-fluid img-thumbnail" src="{{asset($slider->image)}}"
                                         alt="{{$slider->alt}}"
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
                                        <a href="{{route('admin.sliders.edit',[$slider->id])}}" class="btn btn-info">изменить</a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#resourceModal"
                                                data-action="{{route('admin.sliders.destroy',[$slider->id])}}">удалить
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

@endsection
