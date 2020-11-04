<div>
    <p>
        <b>Заказ: № </b> {{$auction_order->order_num}}
    </p>
    <p>
        <b>Наименование товара :</b> <a
            href="{{route('auction.index',$auction_order->auction_id)}}">{{$auction_order->auction->title}}</a>
    </p>
    <p>
        <b>Сумма оплаты :</b> {{$auction_order->price()}} руб.
    </p>

    <hr>
    <p>
        <b>Персональные данные </b>
    </p>
    <p>
        <b>ФИО
            :</b> {{$auction_order->user->lname ?? ''}} {{$auction_order->user->fname ?? ''}} {{$auction_order->user->mname ?? ''}}
    </p>
    <p>
        <b>Страна :</b> {{$auction_order->user->country ?? ''}}
    </p>
    <p>
        <b>Индекс :</b> {{$auction_order->user->postcode ?? ''}}
    </p>
    <p>
        <b>Регион :</b> {{$auction_order->user->region ?? ''}}
    </p>
    <p>
        <b>Город :</b> {{$auction_order->user->city ?? ''}}
    </p>
    <p>
        <b>Адрес :</b> {{$auction_order->user->street ?? ''}}
    </p>
    <p>
        <b>Номер телефона :</b> {{$auction_order->user->login() ?? ''}}
    </p>
    <p>
        <b>Электронная почта :</b> {{$auction_order->user->email ?? ''}}
    </p>
    <p>
        <b>Платежная система :</b> {{$auction_order->user->paymentType() ?? ''}}
    </p>
    <p>
        <b>№ карты или счёта :</b> {{$auction_order->user->ccnum ?? ''}}
    </p>
</div>
