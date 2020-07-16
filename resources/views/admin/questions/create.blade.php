@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{route('admin.questions.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <a href="{{route('admin.pages.howitworks')}}"
                           class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="text">назад</span>
                        </a>
                        <label for="title">Наименование </label>
                        <input type="text" name="title" value="{{old('title')}}" class="form-control @error('title') is-invalid @enderror" id="title">
                        @error('title')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea rows="6" name="description" class="form-control ck__textarea @error('description') is-invalid @enderror" id="description">{{old('description')}}</textarea>
                        @error('description')
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
