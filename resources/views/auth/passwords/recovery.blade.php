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
                /*text-align: center;*/
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
                box-sizing: border-box !important;
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
        <form method="POST" action="{{ route('reset.password.change.success') }}">
            @csrf
            <h1 class='title'>Восстановление пароля</h1>

            <label for="new_password">Новый пароль:
                <input type="password" id="new_password" name="new_password">
            </label>
            @error('new_password')
            <small class="is-invalid">{{$message}}</small><br>
            @enderror
            <label for="new_confirm_password">Подтвердите пароль
                <input type="password" id="new_confirm_password" name="new_confirm_password">
            </label>
            @error('new_confirm_password')
            <small class="is-invalid">{{$message}}</small><br>
            @enderror
            <input class='registration sub' type="submit" value="Сохранить">
        </form>
    </div>
@endsection
