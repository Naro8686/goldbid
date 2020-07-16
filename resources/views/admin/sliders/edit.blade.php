@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{route('admin.sliders.update',[$slider->id])}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <a href="{{route('admin.pages.home')}}"
                           class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="text">назад</span>
                        </a>
                        <label for="alt">Alt текст</label>
                        <input type="text" name="alt" value="{{$slider->alt}}" class="form-control @error('alt') is-invalid @enderror" id="alt" placeholder="alt">
                        @error('alt')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="thumbnail text-center">
                        <img src="{{asset($slider->image)}}"
                             class="img-fluid img-thumbnail mb-2"
                             alt="{{$slider->alt}}"
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
                <div class="col-md-12 mt-3">
                    <button class="btn btn-block btn-outline-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
