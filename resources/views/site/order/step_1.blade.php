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
                    <h2 style="font-size: 19px;margin: 10px 0 20px 0;">Шаг 1. Создание заказа</h2>
                    <p>
                        {!! $data['text'] !!}
                    </p>
                    <div class="order-div">
                        <div class="order-desc">
                            <div class="order-desc-name">
                                <div style="font-weight: bold;color: #999;font-family: sans-serif">Номер Вашего
                                    заказа: <span style="color: #3e8ccb;">{{$data['order_num']}}</span></div>
                                <div class="order-name">
                                    <a style="color:black; text-decoration:underline;"
                                       target="_blank"
                                       href='{{route('auction.index',$data['auction_id'])}}'>{{$data['title']}}</a>
                                </div>
                            </div>
                            <div class="order-img">
                                <img style="height:116px;" src="{{asset($data['img'])}}" alt="{{$data['alt']}}">
                            </div>
                        </div>
                        <div class="order-price">
                            @if($data['winner'])
                                <div class="o-item" style="margin-top: 130px;font-weight:bold;">
                                    <div>Аукционная стоимость:</div>
                                    <div>{{$data['auction_price']}} руб.</div>
                                </div>
                            @else
                                <div class="o-item">
                                    <div>Стоимость товара:</div>
                                    <div>{{$data['full_price']}} руб.</div>
                                </div>
                                <div class="o-item">
                                    <div>Стоимость сделанных Ставок:</div>
                                    <div>{{$data['bid_price']}} руб.</div>
                                </div>
                                <div class="o-item">
                                    <div>Доставка:</div>
                                    <div>0 руб.</div>
                                </div>
                                <div class="o-item result">
                                    <div>Итого:</div>
                                    <div><span id='orderTotal'>{{$data['total_price']}}</span> руб.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table style='width: 100%'>
                        <tr>
                            <td style='width:50%; text-align: left;'>
                                <a class="button__app" href="{{route('auction.index',$data['auction_id'])}}">НАЗАД</a>
                            </td>
                            <td style='width:50%; text-align: right;'>
                                <a class="button__app"
                                   href="{{route('payment.auction.order',['id'=>$data['auction_id'],'step'=>'2'])}}">ПРОДОЛЖИТЬ</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
