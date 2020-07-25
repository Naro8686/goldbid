@extends('layouts.site')
@section('name-page')Пакеты ставок@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/coupon.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <p class="title">1. Выберите пакет ставок</p>
            <p>Чем крупнее пакет ставок выберите, тем больше получите Бонусов! Это даст огромное преимущество перед
                другими игроками!</p>
            <div class="blocks">
                @foreach($packages as $package)
                    <div class="kupon">
                        <label data-id="{{$package->id}}" data-bet="{{$package->bet}}" data-bonus="{{$package->bonus}}"
                               data-price="{{$package->price}}" class="uncheck"></label>
                        <img src="{{asset($package->image)}}" alt="{{$package->alt}}">
                        @if((int)$package->bonus)
                            <div class="kupon-bonus">{{'+'.$package->bonus.' Бонусов'}}</div>
                        @endif
                        <p class="price">{{$package->bet.' ставок'}} <br> {{$package->price.' руб'}}</p>
                    </div>
                @endforeach
            </div>
            <p class="title">2. Выберите способ оплаты</p>
            <div class="payment">
                @foreach($payments as $payment)
                    <div class="pay" data-id="{{$payment['id']}}">
                        <img src="{{asset($payment['img'])}}" alt="img">
                    </div>
                @endforeach
            </div>


            {{--            <div class="center" style="display:block;">--}}
            {{--                <form name="form" method="POST" onsubmit="return validate()"--}}
            {{--                      action="https://money.yandex.ru/quickpay/confirm.xml">--}}
            {{--                    <input type="hidden" name="receiver" value="410016649153663">--}}
            {{--                    <input type="hidden" name="label" value="$order_id">--}}
            {{--                    <input type="hidden" name="quickpay-form" value="shop">--}}
            {{--                    <input type="hidden" name="targets" value="транзакция {order_id}">--}}
            {{--                    <input type="hidden" name="sum" value="" data-type="number">--}}
            {{--                    <input type="hidden" name="comment" value="coupon">--}}
            {{--                    <input type="hidden" name="paymentType" value="">--}}
            {{--                    <input type="hidden" name="successURL" value="{{route('site.home')}}">--}}
            {{--                    <input class="buy" name="pay" type="submit" value="Купить пакет ставок">--}}
            {{--                </form>--}}
            {{--            </div>--}}
            <div class="center" style="display:block;">
                <form name="form" method="POST" onsubmit="return validate()"
                      action="{{route('payment.coupon.buy')}}">
                    @csrf
                    @auth
                        <p class="title">3. Заказ </p>
                        <br>
                        <div>
                            <p>Номер Вашего заказа: <b id="order">{{App\Settings\Setting::orderNumCoupon(auth()->id())}}</b></p>
                            <p>Наименование товара: <b>Пакет ставок <span id="bet">0</span></b></p>
                            <p>Количество бонусов: <b id="bonus">0</b></p>
                            <p>Итоговая стоимость: <b id="price">0</b> <b>руб</b></p>
                        </div>
                        <br><br>
                        @if(auth()->user()->email)
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
                                    <input class="@error('email')is-invalid @enderror" style="width: 200px;height: 30px"
                                           type="email" name="email">
                                    @error('email')
                                    {{$message}}
                                    @enderror
                                </label>
                            </div>
                        @endif
                    @endauth
                    <input type="hidden" name="coupon_id" id="coupon_id">
                    <input type="hidden" name="payment_id" id="payment_id">
                    <br><br>
                    <div style="text-align: center">
                        <input class="buy" type="submit" value="Купить пакет ставок">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(".main .payment .pay").click(function (e) {
                e.preventDefault();
                $(".main .payment .pay").removeClass('active');
                $(this).addClass('active');
            });


            $('.uncheck').click(function () {
                let coupon_id = $(this).attr("data-id");
                for (const [key, value] of Object.entries($(this).data())) {
                    $(`#${key}`).text(`${value}`);
                }
                $(this).removeClass('uncheck');
                $('.check').removeClass('check').addClass('uncheck');
                $(this).addClass('check');
                $('[name="coupon_id"]').val(coupon_id);
            });

            function validate() {
                let payment_id = $('[name="payment_id"]').val();
                let coupon_id = $('[name="coupon_id"]').val();
                if (payment_id.length === 0) {
                    alert("Выберите метод оплаты");
                    return false;
                }
                if (coupon_id.length === 0) {
                    alert("Выберите купон");
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
@endsection
