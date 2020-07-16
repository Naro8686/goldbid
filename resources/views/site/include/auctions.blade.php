@foreach($auctions as $auction)
    <div class="card @if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE) active @endif" data-auction-id="{{$auction['id']}}">
        <div class="favorites">
            <span @if($auction['favorite']) class="active" @endif></span>
        </div>

        <a href="{{route('auction.index',$auction['id'])}}">
            <img class="product-img"
                 src="{{$auction['img']}}"
                 alt="{{$auction['alt']}}">
        </a>
        <div class="name">
            <a title="Подсказка" href="{{route('auction.index',$auction['id'])}}">{{$auction['title']}}</a>
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
                    <p title="Шаг ставки">{{$auction['step_price']}}<br><span>руб</span></p>
                </div>
                <div class="tooltip second">
                    <p>Шаг ставки</p>
                </div>
            </div>
            @if($auction['exchange'])
                <div class="con-tooltip top">
                    <div class="circl">
                        <img title="Возможнось получить вместо выигранного товара &quot;ставки&quot;"
                             src="{{asset('site/img/if_Update_984748.png')}}" alt="">
                    </div>

                    <div class="tooltip three">
                        <p>Возможнось получить вместо выигранного товара "ставки"</p>
                    </div>
                </div>
            @endif

            @if($auction['buy_now'])
                <div class="con-tooltip top">
                    <a href="order.php?id=269&amp;step=1">
                        <div class="circl">
                            <img title="Купить сейчас за {{$auction['full_price']}} руб"
                                 src="{{asset('site/img/if_business_finance_money-05_2784238.png')}}"
                                 alt="">
                        </div>
                    </a>
                    <a href="order.php?id=269">
                        <div class="tooltip four">
                            <p>Купить товар</p>
                        </div>
                    </a>
                </div>
            @endif
        </div>
        @if($auction['status'] === \App\Models\Auction\Auction::STATUS_PENDING)
            <div class="inf">
                <p class="timer">До начала <span class="to__start" data-countdown="{{$auction['start']}}"></span></p>
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
                <button>Ставка</button>
            </div>
        @endif
        @if($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED)
            @if(is_null($auction['winner']))
                <div class="lenta not__win">Не состоялся</div>
                <div class="inf"></div>
                <div class="btn not__win">
                    <span class="price">{{$auction['price']}} руб</span>
                    <button>{{$auction['price']}} руб</button>
                </div>
            @else
                <div class="inf">
                    <p class="winner">{{$auction['winner']}}</p>
                    <p class="price">{{$auction['price']}} руб</p>
                </div>
                @if(!$auction['my_win'])
                    <div class="lenta close">ЗАВЕРШЕН</div>
                    <div class="btn close">
                        <span class="price">{{$auction['price']}} руб</span>
                        <button>Аукцион закрыт</button>
                    </div>
                @else
                    <div class="btn win">
                        <span class="price">{{$auction['price']}} руб</span>
                        <button>Оформит заказ</button>
                    </div>
                @endif
            @endif
        @endif
    </div>
@endforeach

