<p class="title">
    {{$auction['title']}}
</p>
<div class="info">
    <div class="circl">
        <p title="Время прибавляемое к таймеру после ставки">
            {{$auction['bid_seconds']}}
            <br><span>сек</span>
        </p>
    </div>
    <div class="circl">
        <p title="Шаг ставки">{{$auction['step_price_info']}}<br><span>коп</span></p>
    </div>
    @if($auction['exchange'])
        <div class="circl">
            <img title='Возможнось получить вместо выигранного товара "ставки"'
                 src="{{asset('site/img/reload-white.png')}}" alt="">
        </div>
    @endif
    @if($auction['buy_now'])
        <div class="circl">
            <a @if($auction['my_win'] && $auction['exchange']) class="my___win" @endif data-id="{{$auction['id']}}" href="{{route('payment.auction.order',['id'=>$auction['id'],'step'=>'1'])}}">
                <img title="Купить сейчас за {{$auction['full_price']}} руб"
                     src="{{asset('site/img/korzina-white.png')}}"
                     alt="">
            </a>
        </div>
    @endif
</div>
@if($auction['status'] === \App\Models\Auction\Auction::STATUS_PENDING)
    <div class="inf__pending">
        <p> Аукцион начнется через</p>
        <span class="to__start countdown"
              data-countdown="{{$auction['start']}}"></span>
    </div>
@endif
@if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE)
    <p class="winner">{{$auction['winner']}}</p>
    <div class="inf__active">
        <span class="price">{{$auction['price']}} руб.</span>
        <span class="countdown" data-countdown="{{$auction['step_time']}}"></span>
        <button class="@if((bool)$auction['autoBid']) disabled @endif">Ставка</button>
    </div>
    <!-- Сюда вставляется автоставки -->
    <form class="auto__bid" method="POST" action="{{route('auction.auto_bid',$auction['id'])}}">
        @csrf

        <input class="auto__bid_inp" min="0" type="number" name="count" value="{{$auction['autoBid']}}" placeholder="введите ставку">
        <button type="submit" class="auto__bid_btn">
            автоставка
        </button>
    </form>
@endif
@if($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED)
    @if(is_null($auction['winner']))
        <p class="winner">Не состоялся</p>
    @else
        <div class="inf__finish">
            <p class="winner">Победитель: {{$auction['winner']}}</p>
            <p class="price__for_winner">Цена для победителя аукциона</p>
            <p class="price">{{$auction['price']}} руб</p>
        </div>
        @if($auction['my_win'])
            <div class="btn win">
                <a @if($auction['my_win'] && $auction['exchange']) class="my___win" @endif data-id="{{$auction['id']}}" href="{{route('payment.auction.order',['id'=>$auction['id'],'step'=>'1'])}}">Оформить заказ</a>
            </div>
        @endif
    @endif
@endif

@if($auction['buy_now'] && !$auction['my_win'])
    <div class="buy__now @if(!($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE) && !($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED)) mt-100 @elseif(($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED) && !$auction['my_win']) mt-90 @endif ">
        <span class="buy__now_price">{{$auction['full_price']}} руб</span>
        <a href="{{route('payment.auction.order',['id'=>$auction['id'],'step'=>'1'])}}" class="buy__now_btn">
            купить сейчас
        </a>
    </div>
    <p class="info__text" @if($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED) style="margin: 0" @endif>Цена с учетом сделанных Вами
        ставок</p>
@endif
<div class="info__my__bid">
    <div class="item">
        <span>Поставлено Ставок</span><span class="bet">{{$auction['bet']}}</span>
    </div>
    <div class="item">
        <span> Поставлено Бонусов</span><span
            class="bonus">{{$auction['bonus']}}</span>
    </div>
</div>
<div class="log-bid">
    <table class="scrolldown">
        <thead>
        <tr>
            <th>Цена</th>
            <th>Участники</th>
            <th>Время</th>
        </tr>
        </thead>
        <tbody>
        @foreach($auction['bids'] as $bid)
            <tr>
                <td>{{$bid['price']}} руб.</td>
                <td>{{$bid['nickname']}}</td>
                <td>{{$bid['created_at']->format('H:i:s')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
