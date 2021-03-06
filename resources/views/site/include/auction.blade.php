<div data-auction-id="{{$auction['id']}}"
     class="card @if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE) active @endif">
    @if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE || $auction['status'] === \App\Models\Auction\Auction::STATUS_PENDING)
        <div class="favorites @if($auction['top']) top @endif">
            <span @if($auction['favorite'] || $auction['top']) class="active" @endif></span>
        </div>
    @endif
    <a href="{{route('auction.index',$auction['id'])}}">
        @if($auction['my_win'] && $auction['status'] === \App\Models\Auction\Auction::STATUS_ERROR)
            <img class="product-img"
                 src="{{asset('site/img/settings/error.jpg')}}"
                 alt="error">
        @else
            <img class="product-img"
                 src="{{$auction['images'][0]['img']}}"
                 alt="{{$auction['images'][0]['alt']}}">
        @endif
    </a>
    <div class="name">
        <a title="{{$auction['title']}}" href="{{route('auction.index',$auction['id'])}}">{{$auction['title']}}</a>
    </div>
    <div class="short__desc">
        <span>{{$auction['short_desc']}}</span>
    </div>
    <div class="info">
        <div class="con-tooltip top">
            <div class="circl">
                <p title="Время прибавляемое к таймеру после ставки">{{$auction['bid_seconds']}}<br>
                    <span>сек</span>
                </p>
            </div>
            <div class="tooltip first">
                <p>Время прибавляемое к таймеру после ставки</p>
            </div>
        </div>
        <div class="con-tooltip top">
            <div class="circl ">
                <p title="Шаг ставки">{{$auction['step_price_info']}}<br><span>коп</span></p>
            </div>
            <div class="tooltip second">
                <p>Шаг ставки</p>
            </div>
        </div>
        @if($auction['exchange'])
            <div class="con-tooltip top">
                <div class="circl">
                    <img title="Возможнось получить вместо выигранного товара &quot;ставки&quot;"
                         src="{{asset('site/img/arrow_black.png')}}" alt="">
                </div>

                <div class="tooltip three">
                    <p>Возможнось получить вместо выигранного товара "ставки"</p>
                </div>
            </div>
        @endif

        @if($auction['buy_now'])
            <div class="con-tooltip top">
                <a @if($auction['my_win'] && $auction['exchange']) class="my___win"
                   @endif data-id="{{$auction['id']}}"
                   href="{{route('payment.auction.order',['id'=>$auction['id'],'step'=>'1'])}}">
                    <div class="circl">
                        <img title="Купить сейчас за {{$auction['full_price']}} руб"
                             src="{{asset('site/img/if_business_finance_money-05_2784238.png')}}"
                             alt="">
                    </div>
                </a>
            </div>
        @endif
    </div>
    @if($auction['status'] === \App\Models\Auction\Auction::STATUS_PENDING)
        <div class="inf">
            <p class="timer">До начала <span class="to__start"
                                             data-countdown="{{$auction['start']}}"></span>
            </p>
        </div>
        <div class="btn">
            <span class="price">{{$auction['start_price']}} руб</span>
            <button>Скоро</button>
        </div>
    @endif
    @if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE)
        <div class="inf">
            <p class="winner">{{$auction['winner']}}</p>
            <p class="timer">
                <span data-countdown="{{$auction['step_time']}}"></span>
            </p>
        </div>
        <div class="btn active">
            <span class="price">{{$auction['price']}} руб</span>
            <button class="@if((bool)$auction['autoBid']) disabled @endif">Ставка</button>
        </div>
    @endif
    @if($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED || $auction['status'] === \App\Models\Auction\Auction::STATUS_ERROR)
        @if(is_null($auction['winner']))
            <div class="lenta not__win">Не состоялся</div>
            <div class="inf"></div>
            <div class="btn not__win">
                <span class="price">{{$auction['price']}} руб</span>
                <button>{{$auction['price']}} руб</button>
            </div>
        @else
            <div class="inf">
                @if($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED)
                    <p class="winner">{{$auction['winner']}}</p>
                    <p class="price">{{$auction['price']}} руб</p>
                @endif
            </div>
            @if(!$auction['my_win'] || $auction['ordered'])
                <div class="lenta close">ЗАВЕРШЕН</div>
                <div class="btn close">
                    <span class="price">{{$auction['price']}} руб</span>
                    <button>Аукцион закрыт</button>
                </div>
            @else
                @if($auction['status'] === \App\Models\Auction\Auction::STATUS_ERROR)
                    <div class="btn error">
                        <button>ОШИБКА</button>
                    </div>
                @else
                    <div class="btn win">
                        <span class="price">{{$auction['price']}} руб</span>
                        <a data-id="{{$auction['id']}}"
                           href="{{route('payment.auction.order',['id'=>$auction['id'],'step'=>'1'])}}"
                           @if($auction['my_win'] && $auction['exchange']) class="my___win" @endif>Оформить заказ</a>
                    </div>
                @endif
            @endif
        @endif
    @endif
</div>
