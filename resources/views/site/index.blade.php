@extends('layouts.site')
@section('slider')
    <div class="slaider">
        <div><img src="{{asset('site/img/slide1.png')}}" alt=""></div>
        <div><img src="{{asset('site/img/slide2.png')}}" alt=""></div>
        <div><img src="{{asset('site/img/slide3.png')}}" alt=""></div>
        <div><img src="{{asset('site/img/slide4.png')}}" alt=""></div>
        <div><img src="{{asset('site/img/slide5.png')}}" alt=""></div>
    </div>
@endsection
@section('title')Аукцион@endsection
@section('name-page')Аукцион@endsection
@push('css')
    <link href="{{asset('site/css/slick.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/style.css')}}" rel="stylesheet">
@endpush
@section('content')
{{--    <div id="defaultCountdown"></div>--}}
    <div class="auction container">
        <div class="favorite"></div>
        <div class="delete-margin">
            <div class="card wait" data-id="{{$tovar['id'] ?? ''}}">
                <div class="favorites"><span name="favorite" id="{{$tovar['id'] ?? '' }}"></span></div>
                <a href="auction.php?id={{$tovar['id']??''}}"><img class="product-img" src="{{$image[0]??''}}"
                                                                   alt=""></a>
                <div class="name"><a href="auction.php?id={{$tovar['id'] ?? '' }}">{{$tovar['name']??''}}</a></div>
                <div class="info">
                    <div class="con-tooltip top">
                        <div class="circl">
                            <p title="Время прибавляемое к таймеру после ставки">{{$tovar['set_timer']??''}}<br><span>сек</span>
                            </p>
                        </div>
                        <div class="tooltip first">
                            <p>Время прибавляемое к таймеру после ставки</p>
                        </div>
                    </div>
                    <div class="con-tooltip top">
                        <div class="circl ">
                            <p title="Шаг ставки">{{$tovar['step_price']??''}}<br><span>руб</span></p>
                        </div>
                        <div class="tooltip second">
                            <p>Шаг ставки</p>
                        </div>
                    </div>
                    @if(!empty($tovar['swap'])&&$tovar['swap'] != 0)
                        <div class="con-tooltip top">
                            <div class="circl">
                                <img title='Возможнось получить вместо выигранного товара "ставки"'
                                     src="{{asset('site/img/if_Update_984748.png')}}" alt="">
                            </div>
                            <div class="tooltip three">
                                <p>Возможнось получить вместо выигранного товара "ставки"</p>
                            </div>
                        </div>
                    @endif
                    @if (!empty($tovar)&&$tovar['target_summ'] != 0)
                        <div class="con-tooltip top">
                            <a href="order.php?id={{$tovar['id'] ?? '' }}&step=1">
                                <div class="circl">
                                    <img title="Купить сейчас за {{round($user_price??0,1)}} руб"
                                         src="{{asset('site/img/if_business_finance_money-05_2784238.png')}}" alt="">
                                </div>
                            </a>
                            <a href="order.php?id={{$tovar['id'] ?? '' }}">
                                <div class="tooltip four">
                                    <p>Купить товар</p>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
                <p style="height: 20px;" class="username">{{$tovar['winer']??''}}</p>
                <p class="timer">{{$timer_auction??''}}</p>
                <div class="btn" action="index.php" method="POST">
                    <input type="hidden" name="id_tovar" value="{{$tovar['id'] ?? '' }}">
                    <input type="hidden" name="price" value="{{$tovar['price']??''}}">
                    <input type="hidden" name="step_price" value="{{$tovar['step_price']??''}}">
                    <button class="price price{{$tovar['id'] ?? '' }}">{{$tovar = number_format($tovar['price']??0,1)}}
                        <span>руб</span></button>
                    <input type="submit" class="bid" id="{{$id??''}}" name="add" value="Ставка">
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function () {
                // let newYear = new Date();
                // newYear = new Date(newYear.getFullYear()+1, 1 - 1, 1);
                // $('div#defaultCountdown').countdown(newYear)
                //     .on('update.countdown', (e) => {
                //         $(e.currentTarget).html(e.strftime('%w weeks %d days %H:%M:%S'));
                //     })
                //     .on('finish.countdown', (e) => {
                //         console.log(e);
                //     });
                $('.slaider').slick({
                    autoplay: true,
                    autoplaySpeed: 3000
                });
            });

            $(".overflow-alert").on("click", function () {
                $(".modal-alert").addClass("none");
                $(".overflow-alert").addClass("none");
            });
        </script>
    @endpush
@endsection
