@extends('layouts.admin')
@section('content')

    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Отзывы</h1>
        @include('admin.includes.pages.seo_update')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Отзывы</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" width="100%" cellspacing="0" align="center">
                        <thead>
                        <tr>
                            <th>Картинка</th>
                            <th>Наименование</th>
                            <th>Описание</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4">
                                <a href="{{route('admin.reviews.create')}}"
                                   class="btn btn-sm btn-success float-right">добавить</a>
                            </th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>
                                    <img class="img-fluid img-thumbnail" src="{{asset($review->image)}}"
                                         alt="{{$review->alt}}"
                                         width="250">
                                </td>
                                <td>
                                    <p class="name">{{$review->title}}</p>
                                </td>
                                <td>
                                    <p class="name">{{$review->description}}</p>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('admin.reviews.edit',[$review->id])}}" class="btn btn-info">изменить</a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#resourceModal"
                                                data-action="{{route('admin.reviews.destroy',[$review->id])}}">удалить
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
