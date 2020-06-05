@extends('layouts.site')
@section('title')Авторизация@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/style.css')}}">
@endpush
@section('content')
    <div class="authenticate">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <h1 class='title'>Авторизация</h1>
            <label>Введите телефон
                <input type="text" name="phone" value="{{old('phone')}}">
                @error('phone')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
            </label>

            <label>Введите Пароль
                <input type="password" name="password">
                @error('password')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
            </label>
            <p class="recovery"><a style="color: #494949; font-size: 14px;"
                                   href="{{route('password.request')}}">Востановить
                    пароль</a></p>
            <p class="recovery"><a style="color: #494949; font-size: 14px;"
                                   href="{{route('register')}}">Регистрация</a>
            </p>
            <button type="submit">Войти</button>
        </form>
    </div>
@endsection
