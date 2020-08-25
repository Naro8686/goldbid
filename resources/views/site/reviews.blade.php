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
            <p class="title" style="text-align: center">Оставить отзыв</p>
            <div class="send-feedback">
                <form action="{{route('site.reviews')}}" method="POST" enctype="multipart/form-data" style="display: flex;flex-direction: column">
                    @csrf
                    <label for="name">Имя</label>
                    <input type="text" placeholder="Для зарегистрированных пользователей ник" name="name" id="name" value="{{old('name')}}">
                    @error('name')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <br><br>
                    <label for="email">E-mail</label>
                    <input type="email" placeholder="Для обратной связи" name="email" id="email" value="{{old('email')}}">
                    @error('email')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <br><br>
                    <label for="message">Сообщение</label>
                    <textarea name="message" id="message" cols="30" rows="10">{{old('message')}}</textarea>
                    @error('message')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <label>
                        <input style="margin-top:10px;width: 15px" type="checkbox"
                               @if(old('personal_data')) checked @endif
                               @error('personal_data') class="fail" @enderror
                               name="personal_data">
                        На <a href="{{Setting::dynamicURL('personal-data')}}">обработку</a> персональных данных
                        согласен
                    </label>
                    <br>
                    @if(config('recaptcha.key'))
                        <div class="g-recaptcha"
                             data-sitekey="{{config('recaptcha.key')}}">
                        </div>
                    @endif
                    @error('g-recaptcha-response')
                    <small class="alert alert-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <div class="buttons">
                        <label>
                            <input type="file" name="file" id="uploade-file">
                            <span>Загрузить фото</span>
                        </label>
                        <input type="submit" value="отправить">
                        @error('file')
                        <small class="alert alert-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </form>
                <div class="img_container">
                    <img alt="img" src="{{asset('site/img/settings/pen.png')}}">
                    <p>Если Вам понравился наш аукцион, или наоборот, есть замечания, напишите об этом. За развёрнутый и обоснованный отзыв мы начислим Вам Бонусы! Много Бонусов!</p>
                </div>
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
