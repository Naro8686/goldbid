@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{route('admin.mailings.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('admin.settings.mailing')}}"
                       class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                        <span class="text">назад</span>
                    </a>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Наименование</label>
                        <input type="text" name="title" value="{{old('title')}}" class="form-control @error('title') is-invalid @enderror" id="title">
                        @error('title')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="subject">Тема</label>
                        <input type="text" name="subject" value="{{old('subject')}}" class="form-control @error('subject') is-invalid @enderror" id="subject">
                        @error('subject')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Текст</label>
                        <textarea rows="6" name="text" class="form-control @error('text') is-invalid @enderror" id="text">{{old('text')}}</textarea>
                        @error('text')
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
