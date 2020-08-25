@extends('layouts.site')
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/feedback.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <div class="title">
                Обратная связь
            </div>

            <form action="{{route('site.feedback')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="name">Имя</label>
                <input placeholder="Для зарегистрированных пользователей ник" type="text" name="name" id="name" value="{{old('name')}}">
                @error('name')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
                <br><br>
                <label for="email">E-mail</label>
                <input placeholder="Для обратной связи" type="email" name="email" id="email" value="{{old('email')}}">
                @error('email')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
                <br><br>
                <label for="theme">Тема обращения</label>

                <select name="theme" id="theme">
                    <option value="">Выберите тему обращение</option>
                    @foreach($themes as $theme)
                        <option value="{{$theme['id']}}"
                                @if(old('theme')==$theme['id']) selected @endif>{{$theme['value']}}</option>
                    @endforeach
                </select>
                @error('theme')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
                <br><br>

                <label for="message">Сообщение</label>
                <textarea placeholder="Напишите здесь то, что поможет нам понять и решить вашу проблему" name="message" id="message" cols="30" rows="10">{{old('message')}}</textarea>
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
                    <label>
                        <input type="file" name="file" id="uploade-file">
                        <span>Загрузить файл</span>
                    </label>
                    <input type="submit" value="отправить">
                </div>
                @error('file')
                <small class="alert alert-danger" role="alert">{{ $message }}</small>
                @enderror
                <div style="display: flex;justify-content: space-between">
                    <span>Почта: {{$contact->email}}</span>
                    <span>Тел.: {{$contact->phone}}</span>
                </div>
            </form>

        </div>
    </div>
@endsection
