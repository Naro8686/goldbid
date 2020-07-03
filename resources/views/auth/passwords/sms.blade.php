@extends('layouts.site')

@section('content')
    @push('css')
        <link href="{{asset('site/css/style.css')}}" rel="stylesheet">
        <link href="{{asset('site/css/custom.css')}}" rel="stylesheet">
        <style>
            .main {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 70px 0;
            }

            .registration.sub {
                width: 100%;
                background-color: #1B8BCB;
                color: #FFFFFF;
                border: none;
                padding: 10px 10px;
                cursor: pointer;
            }

            .main form {
                max-width: 320px;
                background-color: #F8F8F8;
                padding: 40px;
                -webkit-box-shadow: 0px 0px 24px -4px rgba(0, 0, 0, 0.75);
                -moz-box-shadow: 0px 0px 24px -4px rgba(0, 0, 0, 0.75);
                box-shadow: 0px 0px 24px -4px rgba(0, 0, 0, 0.75);
            }

            .main form h1 {
                font-size: 24px;
                margin-top: 0;
                text-align: center;
            }

            .main form input[type="text"], .main form input[type="password"] {
                -webkit-box-shadow: inset 0px 0px 24px -10px rgba(0, 0, 0, 0.75);
                -moz-box-shadow: inset 0px 0px 24px -10px rgba(0, 0, 0, 0.75);
                box-shadow: inset 0px 0px 24px -10px rgba(0, 0, 0, 0.75);
                border: none;
                outline: none;
            }

            .main form input {
                margin: 5px 0;
                padding: 5px;
                width: 100%;
            }

            .main form p {
                margin-bottom: 10px;
            }

            .main form .recovery a {
                font-size: 12px;
                text-decoration: underline;
                color: #494949;
            }

            .main form p.warning {
                color: #ea203f;
                text-align: center;
            }

        </style>
    @endpush

    <div class="main">
        <form method="POST" action="{{ route('reset.check.phone') }}">
            @csrf
            <h1 class='title'>Восстановление пароля</h1>
            @if (session('error'))
                <p class="warning">{{session('error')}}</p>
            @endif
            @if (!session('reset_id'))
                <label for="phone_number">Введите логин
                    <input type="text" id="phone_number" name="phone" value="{{old('phone')}}">
                </label>
                @error('phone')
                <small class="is-invalid">{{$message}}</small>
                @enderror
            @else
                <label for="sms_code">Подтвердите код
                    <input type="text" id="sms_code" name="sms_code" placeholder="подтвердите код">
                </label>
                @error('sms_code')
                <small class="is-invalid">{{$message}}</small>
                @enderror
            @endif
            <input class='registration sub' type="submit" value="Восстановить">
        </form>
    </div>
@endsection
