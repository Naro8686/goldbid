@extends('layouts.admin')
@section('content')
    <div class="container-fluid">


        <form action="{{route('admin.reviews.update',[$review->id])}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('admin.pages.reviews')}}"
                       class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                        <span class="text">назад</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alt">Alt текст</label>
                        <input type="text" name="alt" value="{{$review->alt}}" class="form-control @error('alt') is-invalid @enderror" id="alt" placeholder="alt">
                        @error('alt')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="thumbnail text-center">
                        <img src="{{asset($review->image)}}"
                             class="img-fluid img-thumbnail w-100 mb-2"
                             alt=""
                             id="imageResult">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="upload" name="file">
                            <label class="custom-file-label" for="upload">Выберите файл</label>
                            @error('file')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Наименование</label>
                        <input type="text" name="title" value="{{$review->title}}" class="form-control @error('title') is-invalid @enderror" id="title">
                        @error('title')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea rows="6" name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{$review->description}}</textarea>
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
