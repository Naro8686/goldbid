@extends('layouts.site')
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/cabinet.css')}}">
@endpush
@section('content')

    <div class="main">
        <div class="container">
            <div class="left">
                <div class="title">
                    Личный кабинет
                </div>
                <div class="info">
                    <div class="wrapper">
                        <button class="no-image" id="img-result"
                                style="background-image: url({{asset($user->avatar())}})"></button>
                    </div>
                    {{--                    <div class="img" style="background-image: url({{asset($user->avatar())}})"></div>--}}
                    <p class="username">{{$user->nickname}}</p>
                    <p class="usermail">{{$user->login()}}</p>
                    <p class="bid">Ставки: <span>{{$user->balance()->bet}}</span></p>
                    <p class="bonus">Бонусы: <span>{{$user->balance()->bonus}}</span></p>
                </div>
                <div id="tabs" class="categories">
                    <h2>Разделы</h2>
                    <ul>
                        <li>
                            <img src="{{asset('site/img/1cat.png')}}" alt="">
                            <a href="{{route('profile.index')}}">
                                <p class="tab @if(request()->is('cabinet')) whiteborder @endif">Мой профиль</p>
                            </a>
                        </li>
                        <li>
                            <img src="{{asset('site/img/2cat.png')}}" alt="">
                            <a href="{{route('profile.personal')}}">
                                <p class="tab @if(request()->is('cabinet/personal')) whiteborder @endif">Персональные
                                    данные</p>
                            </a>
                        </li>
                        <li>
                            <img src="{{asset('site/img/3cat.png')}}" alt="">
                            <a href="{{route('profile.balance')}}">
                                <p class="tab @if(request()->is('cabinet/balance')) whiteborder @endif">Баланс</p>
                            </a>
                        </li>
                        <li>
                            <img src="{{asset('site/img/4cat.png')}}" alt="">
                            <a href="{{route('profile.auctions_history')}}">
                                <p class="tab @if(request()->is('cabinet/auction-history')) whiteborder @endif">История
                                    моих аукционов</p>
                            </a>
                        </li>
                        <li>
                            <img src="{{asset('site/img/5cat.png')}}" alt="">
                            <a href="{{route('profile.referral_program')}}">
                                <p class="tab @if(request()->is('cabinet/referral-program')) whiteborder @endif">
                                    Реферальная программа</p>
                            </a>
                        </li>
                        <li>
                            <img src="{{asset('site/img/4cat.png')}}" alt="">
                            <a href="{{route('logout')}}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <p class="tab">Выйти</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @yield('page')
        </div>
    </div>
    @push('js')
        <script>
            (function () {
                let uploader = document.createElement('input'),
                    image = document.getElementById('img-result'),
                    avatar = document.getElementById('avatar');
                uploader.type = 'file';
                uploader.accept = 'image/*';
                image.onclick = function () {
                    uploader.click();
                }
                uploader.onchange = function () {
                    let reader = new FileReader();
                    let form = new FormData();
                    reader.onload = function (evt) {
                        image.classList.remove('no-image');
                        image.style.backgroundImage = 'url(' + evt.target.result + ')';
                        avatar.style.backgroundImage = 'url(' + evt.target.result + ')';
                    }
                    form.append('file', uploader.files[0]);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{route('profile.index')}}",
                        method: "POST",
                        data: form,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.success === true) {
                                reader.readAsDataURL(uploader.files[0]);
                            }
                        },
                        error: function (err) {
                            window.location.reload();
                        }
                    })
                }
            })();
        </script>
    @endpush
@endsection
