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
        <p title="Шаг ставки">{{$auction['step_price']}}<br><span>руб</span></p>
    </div>
    @if($auction['exchange'])
        <div class="circl">
            <img title='Возможнось получить вместо выигранного товара "ставки"'
                 src="{{asset('site/img/reload-white.png')}}" alt="">
        </div>
    @endif
    @if($auction['buy_now'])
        <div class="circl">
            <a href="order.php?id={{$auction['id']}}&step=1">
                <img title="Купить сейчас за {{$auction['full_price']}} руб"
                     src="{{asset('site/img/korzina-white.png')}}"
                     alt="">
            </a>
        </div>
    @endif
</div>
@if($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED)
    <div style="margin-bottom: 10px;"
         class="name">
        {{--        <? if ($tovar['winer'] == ''): ?>--}}
        {{--        Не состоялся--}}
        {{--        <? else: ?>--}}
        {{--        Победил: <?= $tovar['winer'] ?>--}}
        {{--        <? endif ?>--}}
    </div>
@endif
@if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE)
    <div class="timer">
        <p>Таймер</p>
        <div class="time" data-countdown="{{$auction['step_time']}}"></div>
    </div>
@endif


<div class="hr"></div>
<!-- Активный аукцион -->
@if($auction['status'] === \App\Models\Auction\Auction::STATUS_ACTIVE)
    <div class="bid" data-auction-id="{{$auction['id']}}">
        <div class="price">
            {{$auction['start_price']}}<span> руб</span>
        </div>
        <div class="btn active">
            <button class="bid-btn">Ставка</button>
        </div>
    </div>
    <!-- Сюда вставляется автоставки -->
    <div class="autobid">
        <p>Введите кол-во<br> ставок</p>
        <form method="POST" action="auction.php?id=">
            <input type="hidden" name="id_tovar" value="">
            <input class="value" name="sum" type="text">
            <input type="submit" class="btn" name="autobid" value="Автоставка">
        </form>
    </div>
    <!-- Закрытый аукцион -->

@elseif($auction['status'] === \App\Models\Auction\Auction::STATUS_FINISHED)
    <div class="bid">
        <div style="border-radius: 30px;" class="price">
            {{$auction['start_price']}}<span> руб</span>
        </div>
    </div>
    <!-- Аукцион ожидает -->
@elseif($auction['status'] === \App\Models\Auction\Auction::STATUS_PENDING)
    <div class="bid">
        <div class="btn">
            <p class="bid-btn" style="border-radius: 30px; padding: 10px; min-width: 200px;box-sizing: border-box ;font-size:14px;">
                До начала: <span class="to__start" data-countdown="{{$auction['start']}}"></span>
            </p>
        </div>
    </div>
@endif
<div style="margin-top:40px;"></div>
<div class="hr"></div>
@if($auction['buy_now'])
<div class="roz-price">
    <div style="margin: 0 0 15px 0;">
        <div class="price">
            {{$auction['full_price']}} <span>руб</span>
        </div>
        <a href="order.php?id=197&amp;step=1" class="bid-btn">
            <div class="btn" style="border: none">
                Купить сейчас
            </div>
        </a>
    </div>
</div>
@endif
<div class="log-bid">
    <table style="height: 130px; color:#fff;">

        <tbody>
        <tr>
            <td>Цена</td>
            <td>Участник</td>
            <td>Время</td>
        </tr>
        <tr style="height: 150px;">
            <td></td>
            <td>Ставок нет</td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>
