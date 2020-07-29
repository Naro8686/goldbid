@extends('layouts.site')
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/coupon.css')}}">
@endpush
@section('content')
    <div style="padding: 10px 0;" class="main">
        <div class="container">
            <div style="margin: 30px 0;" class='content-block'>
                <div class='order-panel'>
                    <h1 style="font-size: 26px;">Оформление заказа</h1>
                    <h2 style="font-size: 19px;margin: 10px 0 20px 0;">Шаг 2. Доставка</h2>
                    <p>
                        {!! $data['text'] !!}
                    </p>
                    <table class="personal__info">
                        @if($data['type'] !== \App\Models\Auction\Step::BET)
                            <tr>
                                <td colspan="2" style="font-size:17px;">
                                    @if($data['type'] === \App\Models\Auction\Step::PRODUCT)
                                        Проверьте информацию о доставке.
                                    @else
                                        Проверьте информацию о получателе денежного перевода
                                    @endif
                                    <a style="color:#03a9f4" href="{{route('profile.personal')}}">Изменить</a></td>
                            </tr>
                            <tr>
                                <td>Фамилия:</td>
                                <td>{{$data['lname']}}</td>
                            </tr>
                            <tr>
                                <td>Имя:</td>
                                <td>{{$data['fname']}}</td>
                            </tr>
                            <tr>
                                <td>Отчество:</td>
                                <td>{{$data['mname']}}</td>
                            </tr>
                        @endif
                        @if($data['type'] === \App\Models\Auction\Step::PRODUCT)
                            <tr>
                                <td>Страна:</td>
                                <td>{{$data['country']}}</td>
                            </tr>
                            <tr>
                                <td>Индекс:</td>
                                <td>{{$data['postcode']}}</td>
                            </tr>
                            <tr>
                                <td>Регион:</td>
                                <td>{{$data['region']}}</td>
                            </tr>
                            <tr>
                                <td>Город:</td>
                                <td>{{$data['city']}}</td>
                            </tr>
                            <tr>
                                <td>Адрес:</td>
                                <td>{{$data['street']}}</td>
                            </tr>
                            <tr>
                                <td>Номер телефона:</td>
                                <td>{{$data['phone']}}</td>
                            </tr>
                        @elseif($data['type'] === \App\Models\Auction\Step::MONEY)
                            <tr>
                                <td>Платежная система:</td>
                                <td>{{$data['payment_type']}}</td>
                            </tr>
                            <tr>
                                <td>№ Карты или Счёта:</td>
                                <td>{{$data['ccnum']}}</td>
                            </tr>
                        @endif
                    </table>
                    <table style='width: 100%'>
                        <tr>
                            <td style='width:50%; text-align: left;'>
                                <a class="button__app"
                                   href="{{route('payment.auction.order',['id'=>$data['auction_id'],'step'=>'1'])}}">НАЗАД</a>
                            </td>
                            <td style='width:50%; text-align: right;'>
                                <a class="button__app"
                                   href="{{route('payment.auction.order',['id'=>$data['auction_id'],'step'=>'3'])}}">ПРОДОЛЖИТЬ</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
