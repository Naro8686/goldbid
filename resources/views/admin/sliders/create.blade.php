@extends('layouts.admin')
@section('content')
    <div class="container-fluid">


        <form action="{{route('admin.sliders.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="thumbnail text-center">
                        <img src="{{asset('site/img/settings/sliders/no__image.png')}}"
                             class="img-fluid img-thumbnail mb-2"
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
                        <label for="alt">Alt текст</label>
                        <input type="text" name="alt" value="" class="form-control @error('alt') is-invalid @enderror" id="alt" placeholder="alt">
                        @error('alt')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Текст для картинки</label>
                        <textarea class="form-control @error('text') is-invalid @enderror" rows="5" name="text" id="text"></textarea>
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
    @push('js')
        <script>
            $(document).on('change', '#upload', function () {
                readURL($(this)[0]);
            });
        </script>
    @endpush
@endsection
