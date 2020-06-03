@extends('layouts.site')
@section('title')Обратная связь@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/feedback.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <div class="title">
                Обратная связь
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <p>Имя</p>
                <input type="text" name="name">
                <p>E-mail</p>
                <input type="text" name="email">
                <p>Сообщение</p>
                <textarea name="message" id="" cols="30" rows="10"></textarea>
                <div class="g-recaptcha" data-sitekey="6LfdH30UAAAAAMq5D9CnM_oZGpmkjHy1p0UqzlsO"></div>
                <div class="buttons">
                    <label>
                        <input type="file" name="userfile" id="uploade-file">
                        <span>Загрузить файл</span>
                    </label>
                    <input type="submit" name="send">
                </div>
                <div style="margin-top: -40px;margin-bottom: 40px;">Почта: GoldBid24@gmail.com
                    <span style="float: right;">Тел.:+7(918)127-47-76</span></div>
            </form>
        </div>
    </div>
@endsection
