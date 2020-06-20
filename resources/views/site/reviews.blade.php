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
                    <li class="slide showing">

                        <img src="{{asset('site/css/img/reviews1.jpg')}}" alt="">
                        <p>
                            <span>Оля г. Симферополь </span><br><br>
                            Друзья, это все правда. Это реальный шанс сэкономить на покупке крутейших гаджетов. Участвую с удовольствием
                        </p>
                    </li>
                    <li class="slide">
                        <img src="{{asset('site/css/img/reviews2.jpg')}}" alt="">
                        <p>
                            <span>Данила г. Иркутск</span><br><br>
                            Это нереально круто! Я купил MAC за копейки! Ребята, вы в это верите вообще?? Моя самая выгодная покупка в жизни!
                        </p>
                    </li>
                    <li class="slide">
                        <img src="{{asset('site/css/img/reviews3.jpg')}}" alt="">
                        <p>
                            <span>Олеся г. Москва</span><br><br>
                            Про аукцион мне рассказала подруга, она давно там отоваривается. Я сразу подумала - развод. А потом ради интереса купила 10 ставок. И сразу выиграла! Азарт и адреналин просто зашкаливают! Буду участвовать ещё.

                        </p>
                    </li>
                    <li class="slide">
                        <img src="{{asset('site/css/img/reviews4.jpg')}}" alt="">
                        <p>
                            <span>Михаил г. Воронеж</span><br><br>
                            Ребята, даже не сомневайтесь. Самый крутой и выгодный шопинг в интернете.
                        </p>
                    </li>
                    <li class="slide">
                        <img src="{{asset('site/css/img/reviews5.jpg')}}" alt="">
                        <p>
                            <span>Аслан г. Сочи</span><br><br>
                            Аааа! Я в шоке! Айфон за 1800 рублей... Я вас люблю GoldBid!
                        </p>
                    </li>
                </ul>
            </div>

            <br>
            <hr>
            <div class="send-feedback">
                <p class="title">Оставить отзыв</p>
                <form action="" method="POST" enctype="multipart/form-data">
                    <p>Имя</p>
                    <input type="text" name="name">
                    <p>Город</p>
                    <input type="text" name="city">
                    <p>Сообщение</p>
                    <textarea name="message" id="" cols="30" rows="10"></textarea>
                    <div class="g-recaptcha" data-sitekey="6LfdH30UAAAAAMq5D9CnM_oZGpmkjHy1p0UqzlsO"></div>
                    <div class="buttons">
                        <input type="submit" name="send">
                        <label>
                            <input type="file" name="userfile" id="uploade-file">
                            <span>Загрузить фото</span>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            var slides = document.querySelectorAll('#slides .slide');
            var currentSlide = 0;
            var slideInterval = setInterval(nextSlide,15000);

            function nextSlide() {
                slides[currentSlide].className = 'slide';
                currentSlide = (currentSlide+1)%slides.length;
                slides[currentSlide].className = 'slide showing';
            }
        </script>
    @endpush
@endsection
