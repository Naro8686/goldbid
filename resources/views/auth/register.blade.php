@extends('layouts.site')
@section('title')Регистрация@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/style.css')}}">
@endpush
@section('content')
    <div class="authenticate">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <h1 class='title'>Регистрация</h1>
            <label>Введите ник
                <input type="text" name="nickname" placeholder="Не более 16 символов"
                       maxlength="16" value="{{old('nickname')}}">
                @error('nickname')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
            </label>
            <label>Введите телефон
                <input type="text" name="phone" placeholder="+7" value="{{old('phone')}}">
                @error('phone')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
            </label>
            <label>Введите пароль
                <input type="password" name="password" autocomplete="new-password"
                       placeholder="Не более 8 символов">
                @error('password')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
            </label>
            <label>Повторите пароль
                <input type="password" name="password_confirmation"
                       autocomplete="new-password">
            </label>
            <label>
                <input style="width: 15px; margin-bottom: 0;" type="checkbox"
                       @if(old('terms_of_use')) checked @endif
                       @error('terms_of_use') class="fail" @enderror
                       name="terms_of_use">
                С <a href="{{Setting::dynamicURL('terms-of-use')}}"> условиями</a> согласен
            </label>
            <label>
                <input style="width: 15px; margin-bottom: 0;" type="checkbox"
                       @if(old('personal_data')) checked @endif
                       @error('personal_data') class="fail" @enderror
                       name="personal_data">
                На <a href="{{Setting::dynamicURL('personal-data')}}">обработку</a> персональных данных
                согласен
            </label>
            <label>
                <input style="width: 15px; margin-bottom: 0;" type="checkbox"
                       @if(old('privacy_policy')) checked @endif
                       @error('privacy_policy') class="fail" @enderror
                       name="privacy_policy">
                С <a href="{{Setting::dynamicURL('privacy-policy')}}">политикой конфиденциальности</a>
                ознакомлен
            </label>
            @if(config('recaptcha.key'))
                <div class="g-recaptcha"
                     data-sitekey="{{config('recaptcha.key')}}">
                </div>
            @endif
            @error('g-recaptcha-response')
            <small class="alert alert-danger" role="alert">{{ $message }}</small>
            @enderror
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
@endsection
