@extends('layouts.admin')
@section('content')
    <div class="container-fluid">

        <form action="{{route('admin.bots.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('admin.bots.index')}}" class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                        <span class="text">назад</span>
                    </a>
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Добавить новое имя бота </h6>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input id="name" class="form-control" name="name" value="{{old('name')}}">
                        @error('name')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button class="btn btn-block btn-outline-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
