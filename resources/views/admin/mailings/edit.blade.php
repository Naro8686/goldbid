@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{route('admin.mailings.update',[$mailing->id])}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
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
                <div class="@if($mailing->type === $mailing::ADS) col-md-12 @else col-md-8 @endif">
                    @if($mailing->type === $mailing::ADS)
                        <div class="form-group">
                            <label for="title">Наименование</label>
                            <input type="text" name="title" value="{{$mailing->title}}"
                                   class="form-control @error('title') is-invalid @enderror" id="title">
                            @error('title')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="subject">Тема</label>
                        <input type="text" name="subject" value="{{$mailing->subject}}"
                               class="form-control @error('subject') is-invalid @enderror" id="subject">
                        @error('subject')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Текст</label>
                        <textarea rows="6" name="text" class="form-control @error('text') is-invalid @enderror"
                                  id="text">{{$mailing->text}}</textarea>
                        @error('text')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
                @if($mailing->type !== $mailing::ADS)
                    <div class="col-md-4">
                        <div class="d-flex justify-content-center align-items-center pt-4 mt-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><h5>Теги которые можно использовать!</h5></li>
                                <li class="list-group-item">
                                    <ul class="">
                                        <li>#nickname#</li>
                                        <li>#login#</li>
                                        <li>#password#</li>
                                        <li>#code#</li>
                                        <li>#order#</li>
                                        <li>#auction#</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-12 mt-3">
                    <button class="btn btn-block btn-outline-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
