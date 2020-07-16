@extends('layouts.site')
@section('name-page')Отзывы@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/reviews.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <div class="feedback">
                <ul id="slides">
                    <i><img onclick="nextSlide()" src="{{asset('site/css/img/Arrow-Right-icon.png')}}" alt=""></i>
                    @foreach($reviews as $key => $review)
                        <li class="slide @if($key===0) showing @endif">
                            <img src="{{asset($review->image)}}" alt="{{$review->alt}}">
                            <p>
                                <span>{{$review->title}}</span><br><br>
                                {{$review->description}}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <br>
            <hr>
            <div class="send-feedback">
                <p class="title">Оставить отзыв</p>
                <form action="{{route('site.reviews')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="name">Имя</label>
                    <input type="text" name="name" id="name" value="{{old('name')}}">
                    @error('name')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <br><br>
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{old('email')}}">
                    @error('email')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <br><br>
                    <label for="message">Сообщение</label>
                    <textarea name="message" id="message" cols="30" rows="10">{{old('message')}}</textarea>
                    @error('message')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <br><br>
                    @if(config('recaptcha.key'))
                        <div class="g-recaptcha"
                             data-sitekey="{{config('recaptcha.key')}}">
                        </div>
                    @endif
                    @error('g-recaptcha-response')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <div class="buttons">
                        <input type="submit" value="отправить">
                        <label>
                            <input type="file" name="file" id="uploade-file">
                            <span>Загрузить фото</span>
                        </label>
                        @error('file')
                        <small class="alert alert-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            var slides = document.querySelectorAll('#slides .slide');
            var currentSlide = 0;
            var slideInterval = setInterval(nextSlide, 15000);

            function nextSlide() {
                slides[currentSlide].className = 'slide';
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].className = 'slide showing';
            }
        </script>
    @endpush
@endsection
