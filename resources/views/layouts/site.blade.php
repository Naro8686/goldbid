<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="{{$page->meta->keywords ?? ''}}">
    <meta name="description" content="{{$page->meta->description ?? ''}}">
    <title>{{isset($page) ? $page->meta->title() : 'GoldBid'}}</title>
    <link rel ="apple-touch-icon" sizes ="180x180" href ="{{asset('apple-touch-icon.png')}}" >
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">
    @stack('css')
    <link href="{{asset('site/css/humburger.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/modal.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/agree_cookie.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/media.css')}}" rel="stylesheet">
</head>

<body>
<div class="response">
    @if ($bonus = session('bonus_modal'))
        @include('site.include.bonus_modal')
    @endif
    @if($message = session('message'))
        @include('site.include.info_modal')
    @endif
</div>
<div class="header">
    <div class="container">
        <div style="flex-direction: column; position: relative;" class="left">
            <a href="{{url('/')}}"><img src="{{asset('site/img/logo.png')}}" alt=""></a>
            <p class="text">отличные товары по безумно низким ценам!</p>
        </div>
        <div class="right">
            @guest
                <div class="authorization">
                    <a href="#" class='registration open-modal' data-modal="#modal1">Быстрая регистрация</a>
                    <a href="#" class='login open-modal' data-modal="#modal2">Вход</a>
                    <div class='modal' id='modal1'>
                        <div class='content'>

                            <form id="register" action="{{ route('register') }}" method="POST">
                                @csrf
                                <h1 class='title'>Регистрация</h1>
                                <p>
                                    <label>Введите ник
                                        <input type="text" name="nickname" placeholder="Не более 16 символов"
                                               maxlength="16">
                                    </label>
                                </p>
                                <p>
                                    <label>Введите телефон
                                        <input type="text" name="phone" placeholder="+7">
                                    </label>
                                </p>
                                <p>
                                    <label>Введите пароль
                                        <input type="password" name="password" autocomplete="new-password"
                                               placeholder="Не менее 8 символов">
                                    </label>
                                </p>
                                <p>
                                    <label>Повторите пароль
                                        <input id="password-confirm" type="password" name="password_confirmation"
                                               autocomplete="new-password">
                                    </label>
                                </p>

                                <p style="display: flex; font-size: 14px;">
                                    <label>
                                        <input style="width: 15px; margin-bottom: 0;" type="checkbox"
                                               name="terms_of_use">
                                        С <a style="text-decoration: underline; color: #494949;"
                                             href="{{Setting::dynamicURL('terms-of-use')}}"> условиями</a> согласен
                                    </label>
                                </p>
                                <p style="display: flex; font-size: 14px;">
                                    <label>
                                        <input style="width: 15px; margin-bottom: 0;" type="checkbox"
                                               name="personal_data">
                                        На <a style="text-decoration: underline; color: #494949;"
                                              href="{{Setting::dynamicURL('personal-data')}}">обработку</a> персональных
                                        данных
                                        согласен
                                    </label>
                                </p>
                                <p style="display: flex; font-size: 14px;">
                                    <label>
                                        <input style="width: 15px; margin-bottom: 0;" type="checkbox"
                                               name="privacy_policy">
                                        С <a style="text-decoration: underline; color: #494949;"
                                             href="{{Setting::dynamicURL('privacy-policy')}}">политикой
                                            конфиденциальности</a>
                                        ознакомлен
                                    </label>
                                </p>
                                @if(config('recaptcha.key'))
                                    <div class="g-recaptcha"
                                         data-sitekey="{{config('recaptcha.key')}}">
                                    </div>
                                @endif
                                <p>
                                    <input class="registration sub" type="submit" name="send"
                                           value="Зарегистрироваться">
                                </p>
                            </form>
                        </div>
                    </div>

                    <div class='modal' id='modal2'>
                        <div class='content'>
                            <form id="login" action="{{ route('login') }}" method="POST">
                                @csrf
                                <h1 class='title'>Войти</h1>
                                <p>
                                    <label>Введите телефон
                                        <input type="text" name="phone">
                                    </label>
                                </p>
                                <p>
                                    <label>Введите Пароль
                                        <input type="password" name="password">
                                    </label>
                                </p>
                                <p class="recovery"><a style="color: #494949; font-size: 14px;"
                                                       href="{{route('reset.password.sms')}}">Востановить
                                        пароль по смс</a></p>
                                <p class="recovery"><a style="color: #494949; font-size: 14px;"
                                                       href="{{route('register')}}">Регистрация</a>
                                </p>
                                <input class='registration sub' type="submit" value="Войти">
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="userin" id="user_{{auth()->id()}}">
                    <div class="balance">
                        <p>Ставки:&nbsp<span class="phpbalance">{{auth()->user()->balance()->bet}}</span></p>
                        <p>Бонусы:&nbsp<span class="phpbonus">{{auth()->user()->balance()->bonus}}</span></p>
                    </div>
                    <div class="accaunt">
                        <a href="{{route('profile.index')}}">
                            <p class="username">{{auth()->user()->nickname}}</p>
                            <div id="avatar" class="avatar"
                                 style="background-image: url({{asset(auth()->user()->avatar())}}); background-position: center; background-size: cover; border-radius: 50%;"></div>
                        </a>
                        <ul class="drop-menu @if(auth()->user()->is_admin) admin__profile @endif">
                            <img src="{{asset('site/img/drop-bg.png')}}" alt="drop">
                            @if(auth()->user()->is_admin)
                                <li>
                                    <img src="{{asset('site/img/1cat.png')}}" alt="">
                                    <a href="{{route('admin.dashboard')}}">
                                        <p>Админ Панель</p>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <img src="{{asset('site/img/1cat.png')}}" alt=""><a
                                        href="{{route('profile.index')}}">
                                        <p>Мой
                                            профиль</p></a>
                                </li>
                                <li><img src="{{asset('site/img/2cat.png')}}" alt=""><a
                                        href="{{route('profile.personal')}}"><p>
                                            Персональные данные</p>
                                    </a>
                                </li>
                                <li><img src="{{asset('site/img/3cat.png')}}" alt=""><a
                                        href="{{route('profile.balance')}}">
                                        <p>
                                            Баланс</p></a></li>
                                <li><img src="{{asset('site/img/4cat.png')}}" alt=""><a
                                        href="{{route('profile.auctions_history')}}"><p>История
                                            аукционов</p></a>
                                </li>
                                <li><img src="{{asset('site/img/5cat.png')}}" alt=""><a
                                        href="{{route('profile.referral_program')}}"><p>
                                            Реферальная программа</p>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <img src="{{asset('site/img/4cat.png')}}" alt=""><a href="{{ route('logout') }}"
                                                                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><p>Выйти</p></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>
@yield('slider')
<div class="nav">
    <div class="container">
        <ul>
            <li @if(!is_null(request()->route()) && request()->route()->getName()==='site.home') class="active" @endif>
                <a
                    href="{{url('/')}}">Аукцион</a></li>
            <li @if(!is_null(request()->route()) && request()->route()->getName()==='site.how_it_works') class="active" @endif>
                <a
                    href="{{route('site.how_it_works')}}">Как это работает</a></li>
            <li @if(!is_null(request()->route()) && request()->route()->getName()==='site.coupon') class="active" @endif>
                <a
                    href="{{route('site.coupon')}}">Пополнить баланс</a></li>
            <li @if(!is_null(request()->route()) && request()->route()->getName()==='site.reviews') class="active" @endif>
                <a
                    href="{{route('site.reviews')}}">Отзывы</a></li>
            <li @if(!is_null(request()->route()) && request()->route()->getName()==='site.feedback') class="active" @endif>
                <a
                    href="{{route('site.feedback')}}">Обратная связь</a></li>
        </ul>
    </div>
</div>
<div class="name-page">
    <p>@yield('name-page')</p>
</div>
<div class="hamburger">
    <button class="menu-toggle"></button>
    <nav>
        <ul class="menu">
            <li data-text="Как это работает"><a href="{{route('site.how_it_works')}}">Как это работает</a></li>
            <li data-text="Пополнить баланс"><a href="{{route('site.coupon')}}">Пополнить баланс</a></li>
            <li data-text="Отзывы"><a href="{{route('site.reviews')}}">Отзывы</a></li>
            <li data-text="Обратная связь"><a href="{{route('site.feedback')}}">Обратная связь</a></li>
            <li data-text="Личный кабинет"><a href="{{route('profile.index')}}">Личный кабинет</a></li>
        </ul>
    </nav>
</div>
@yield('content')
<div class="footer">
    <div class="payment-methods">
        <div class="container" style="display: none">
            <img src="{{asset('site/img/payment.png')}}" alt="">
        </div>
        <ul>
            <li class="visa"></li>
            <li class="master"></li>
            <li class="maestro"></li>
            <li class="mir"></li>
            <li class="sberbank"></li>
            <li class="yandex"></li>
            <li class="qiwi"></li>
            <li class="mts"></li>
            <li class="megafon"></li>
            <li class="beeline"></li>
            <li class="tele2"></li>
        </ul>
    </div>
    <div class="container">
        <nav>
            <ul>
                <li class="logo">
                    <a href="{{url('/')}}"><img src="{{asset('site/img/logo-footer.png')}}" alt=""></a>
                </li>
                <li>
                    <p class="description">
                        Мы работаем для вашей выгоды!
                    </p>
                </li>
            </ul>
        </nav>
        <nav class="social">
            <ul>
                @if(isset($page))
                    @foreach($page->footer->social as $social)
                        <li>
                            <img src="{{asset($social->icon)}}" alt="">
                            <a href="{{$social->link}}" target="_blank">
                                <span>{{$social->name}}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>
        <nav>
            <ul>
                @if(isset($page))
                    @foreach($page->footer->left as $left)
                        <li><a href="{{url($left->link)}}">{{$left->name}}</a></li>
                    @endforeach
                @endif
            </ul>
        </nav>
        <nav style="text-align: right">
            <ul>
                @if(isset($page))
                    @foreach($page->footer->right as $right)
                        <li><a href="{{url($right->link)}}">{{$right->name}}</a></li>
                    @endforeach
                @endif
            </ul>
        </nav>
    </div>

</div>

<div class="down-footer">
    <div class="container">
        <p class="info">&copy; {{config('app.name').' '.date('Y')}} - Все права защищены</p>
    </div>
</div>

@if(!request()->hasCookie('cookiesPolicy'))
    <div class="down-footer fix">
        <div class="agree_cookie">
            <div class="container">
                <div>
                    Мы используем файлы cookie. Продолжая использовать сайт, вы соглашаетесь с <a
                        href="{{Setting::dynamicURL('cookie-terms-of-use')}}">условиями использования</a> файлов cookie.
                </div>
                <button class="cookie__btn agree_cookie_btn" data-agree="1">Согласен</button>
                <button class="cookie__btn close" data-agree="0">x</button>
            </div>
        </div>
    </div>
@endif
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('site/js/jquery.js')}}"></script>
<script src="{{asset('site/js/jquery.countdown.min.js')}}"></script>
<script src="{{asset('site/js/events.js')}}"></script>
<script src="{{asset('site/js/slick.js')}}"></script>
<script src="{{asset('site/js/prefixfree.min.js')}}" async></script>
<script src="{{asset('site/js/jquery.cookie.js')}}"></script>
<script src="{{asset('site/js/imask.js')}}"></script>
<script src="{{asset('site/js/main.js')}}"></script>
<script src="{{asset('site/js/humburger.js')}}"></script>
<script src="{{asset('site/js/modal.js')}}"></script>
<script src="{{asset('site/js/custom.js')}}"></script>

<script src='https://www.google.com/recaptcha/api.js' async defer></script>
@stack('js')
</body>
</html>
