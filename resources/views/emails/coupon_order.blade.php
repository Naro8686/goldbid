<div>
    <p>
        <b>Заказ: № </b> {{$coupon_order->order}}
    </p>
    <p>
        <b>Наименование товара :</b> Пакет ставок {{$coupon_order->coupon->bet}}
    </p>
    <p>
        <b>Количество бонусов :</b> {{$coupon_order->coupon->bonus}}
    </p>
    <p>
        <b>Сумма оплаты :</b> {{$coupon_order->coupon->price}} руб.
    </p>

</div>
