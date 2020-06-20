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
                <div class="kupon">
                    <label id="10" class="uncheck"></label>
                    <input id='one' name="select" type='text' value="10"/>
                    <img src="{{asset('site/img/10bid.png')}}" alt="">
                    <p class="price">10 ставок <br> 100 руб</p>
                </div>
                <div class="kupon">
                    <label id="50" class="uncheck"></label>
                    <img src="{{asset('site/img/50bid.png')}}" alt="">
                    <div class="kupon-bonus">+5 Бонусов</div>
                    <p class="price">50 ставок <br> 500 руб</p>
                </div>
                <div class="kupon">
                    <label id="100" class="uncheck"></label>
                    <img src="{{asset('site/img/100bid.png')}}" alt="">
                    <div class="kupon-bonus">+20 Бонусов</div>
                    <p class="price">100 ставок <br> 1000 руб</p>
                </div>
                <div class="kupon">
                    <label id="500" class="uncheck"></label>
                    <img src="{{asset('site/img/500bid.png')}}" alt="">
                    <div class="kupon-bonus">+150 Бонусов</div>
                    <p class="price">500 ставок <br> 5000 руб</p>
                </div>
                <div class="kupon">
                    <label id="1000" class="uncheck"></label>
                    <img src="{{asset('site/img/1000bid.png')}}" alt="">
                    <div class="kupon-bonus">+500 Бонусов</div>
                    <p class="price">1000 ставок <br> 10000 руб</p>
                </div>
                <!-- <div class="kupon">
                    <input id='six' name="select" type='radio' />
                    <label class="check" for='six'>
                        <span></span>
                    </label>
                    <img src="img/10bonus.png" alt="">
                    <p class="price">10 бонусов <br> 500 руб</p>
                </div>
                <div class="kupon">
                    <input id='seven' name="select" type='radio' />
                    <label class="check" for='seven'>
                        <span></span>
                    </label>
                    <img src="img/50bonus.png" alt="">
                    <p class="price">50 бонусов <br> 500 руб</p>
                </div>
                <div class="kupon">
                    <input id='eight' name="select" type='radio' />
                    <label class="check" for='eight'>
                        <span></span>
                    </label>
                    <img src="img/100bonus.png" alt="">
                    <p class="price">100 бонусов<br> 500 руб</p>
                </div>
                <div class="kupon">
                    <input id='nine' name="select" type='radio' />
                    <label class="check" for='nine'>
                        <span></span>
                    </label>
                    <img src="img/500bonus.png" alt="">
                    <p class="price">500 бонусов <br> 500 руб</p>
                </div>
                <div class="kupon">
                    <input id='ten' name="select" type='radio' />
                    <label class="check" for='ten'>
                        <span></span>
                    </label>
                    <img src="img/1000bonus.png" alt="">
                    <p class="price">1000 бонусов <br> 500 руб</p>
                </div> -->
            </div>
            <p class="title">2. Выберите способ оплаты</p>
            <div class="payment">
                <div id="AC" class="pay">
                    <img src="{{asset('site/img/payment/visa.png')}}" alt=""
                         style="position: absolute;height: 39px;top: -1px;">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/mastercard.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/Maestro.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/Mir-logo.jpg')}}" alt=""
                         style="position: absolute;height: 39px;top: 0;">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/sberbank.jpg')}}" alt=""
                         style="position: absolute;top: 0px;height: 39px;">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/yandex.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/qiwi.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/mts.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/megafon.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/beeline.png')}}" alt="">
                </div>
                <div class="pay">
                    <img src="{{asset('site/img/payment/tele2.png')}}" alt="">
                </div>

            </div>
            <div class="center" style="display:block;">
                <form name="form" method="POST" style="text-align:center" onsubmit="return validate()"
                      action="https://money.yandex.ru/quickpay/confirm.xml">
                    <input type="hidden" name="receiver" value="410016649153663">
                    <input type="hidden" name="label" value="{{$uservalue['id']??''}}">
                    <input type="hidden" name="quickpay-form" value="shop">
                    <input type="hidden" name="targets" value="{{$_SESSION['user']??''}}">
                    <input type="hidden" name="sum" value="" data-type="number">
                    <input type="hidden" name="successURL" value="{{route('site.home')}}">
                    <input type="hidden" name="paymentType" value="">
                    <p style="text-align:left">В соответствии с ФЗ №54 при онлайн-оплате кассовый чек будет
                        предоставлен в электронном виде на указанный при регистрации адрес электронной почты или в
                        СМС сообщении</p><br/>

                    <input class="buy" name="pay" type="submit" value="Купить пакет ставок">
                </form>
            </div>

        </div>

    </div>
    @push('js')
        <script>
            function validate() {
                //Считаем значения из полей name и email в переменные x и y
                var paymentType = $('[name = paymentType]').val();
                var sum = $('[name = sum]').val();
                //Если поле name пустое выведем сообщение и предотвратим отправку формы
                if (paymentType.length === 0) {
                    alert("Выберите метод оплаты");
                    return false;
                }
                //Если поле email пустое выведем сообщение и предотвратим отправку формы
                if (sum.length === 0) {
                    alert("Выберите купон");
                    return false;
                }
            }

            function getvalue(id) {
                var val = $('#' + id).val();
                $('[name = sum]').val(val * 10);
            }

            function getpayment(payment) {
                $('[name = paymentType]').val(payment);
            }

            $(function () {
                $('[name = select]').on("click", function () {
                    var id = $(this).attr("id");
                    getvalue(id);
                });
                $('.pay').on("click", function () {
                    var payment = $(this).attr("id");
                    getpayment(payment);
                });
            })

        </script>
    @endpush
@endsection
