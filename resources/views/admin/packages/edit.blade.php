@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{route('admin.packages.update',[$package->id])}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('admin.pages.coupon')}}"
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
                        <input type="text" name="alt" value="{{$package->alt}}"
                               class="form-control @error('alt') is-invalid @enderror" id="alt" placeholder="alt">
                        @error('alt')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="thumbnail text-center">
                        <img src="{{asset($package->image)}}"
                             class="img-fluid img-thumbnail w-100 mb-2"
                             alt=""
                             id="imageResult">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="upload"
                                   name="file">
                            <label class="custom-file-label" for="upload">Выберите файл</label>
                            @error('file')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bet">Ставок</label>
                        <input type="number" name="bet" value="{{$package->bet}}" min="0"
                               class="form-control @error('bet') is-invalid @enderror" id="bet">
                        @error('bet')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bonus">Бонусов</label>
                        <input type="number" name="bonus" value="{{(int)$package->bonus}}" min="0"
                               class="form-control @error('bonus') is-invalid @enderror" id="bonus">
                        @error('bonus')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Цена</label>
                        <input type="number" name="price" value="{{$package->price}}" min="0"
                               class="form-control @error('price') is-invalid @enderror" id="price">
                        @error('price')
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
