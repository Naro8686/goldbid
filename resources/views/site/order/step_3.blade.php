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
                    <h2 style="font-size: 19px;margin: 10px 0 20px 0;">Шаг 3. Оплата</h2>
                    <p class="title">Выберите способ оплаты</p>
                    <div class="payment">
                        @foreach($payments as $payment)
                            <div class="pay" data-id="{{$payment['id']}}">
                                <img src="{{asset($payment['img'])}}" alt="img">
                            </div>
                        @endforeach
                    </div>
                    <form name="form" method="POST" onsubmit="return validate()"
                          action="{{route('payment.auction.buy')}}">
                        @csrf
                        <br>
                        <div>
                            <p>Номер Вашего заказа: <b
                                    id="order">{{$data['order_num']}}</b></p>
                            <p>Наименование товара: <b>{{$data['title']}}</b></p>
                            <p>Итоговая стоимость: <b id="price">{{$data['price']}}</b> <b>руб</b></p>
                        </div>
                        <br><br>
                        @if($data['email'])
                            <div>
                                <p>Кассовый чек будет предоставлен в электроном виде на адрес электронной почты ,
                                    указаний в
                                    личном
                                    кабинете </p>
                            </div>
                        @else
                            <div>
                                <label>
                                    Укажите адрес электронный почты
                                    <input class="@error('email')is-invalid @enderror"
                                           style="width: 200px;height: 30px"
                                           type="email" name="email">
                                    @error('email')
                                    {{$message}}
                                    @enderror
                                </label>
                            </div>
                        @endif
                        <input type="hidden" name="payment_id" id="payment_id">
                        <input type="hidden" name="order_num" value="{{$data['order_num']}}">
                        <table style='width: 100%;margin-top: 25px'>
                            <tr>
                                <td style='width:50%; text-align: left;'>
                                    <a class="button__app"
                                       href="{{route('payment.auction.order',['id'=>$data['auction_id'],'step'=>'2'])}}">НАЗАД</a>
                                </td>
                                <td style='width:50%; text-align: right;'>
                                    <button class="button__app buy"
                                            type="submit">ОПЛАТИТЬ
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(".main .payment .pay").click(function (e) {
            e.preventDefault();
            $(".main .payment .pay").removeClass('active');
            $(this).addClass('active');
        });
        function validate() {
            let payment_id = $('[name="payment_id"]').val();
            if (payment_id.length === 0) {
                alert("Выберите метод оплаты");
                return false;
            }
        }

        function getpayment(id) {
            $('[name="payment_id"]').val(id);
        }
        $('.pay').on("click", function () {
            let payment_id = $(this).data("id");
            getpayment(payment_id);
        });
    </script>
@endpush
