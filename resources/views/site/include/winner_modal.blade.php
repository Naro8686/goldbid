<div class="notify__modal">
    <div class="notify__item">
        <button class="notify__modal__btn__close btn__close">X</button>
        <div class="notify__modal__body">
            <div class="win__info_container">
                <div class="image__container">
                    <p>{{$data['title']}}</p>
                    <img src="{{asset($data['image'])}}" alt="{{$data['alt']}}">
                </div>
                <div class="info__container">
                    <div class="info__container_absolute">
                        <div class="left__item">
                            <p>{{$data['price']}}</p>
                            <span>рублей</span>
                            <a href="{{route('payment.auction.order',['id'=>$data['id'],'step'=>'1'])}}">оплатить</a>
                            <span style="margin: auto">После оплаты мы отправим Вам выигранный лот </span>
                            <img width="40px" src="{{asset('site/img/gift.png')}}" alt="gift">
                        </div>
                        <div class="right__item">
                            <p>{{$data['bet']}}</p>
                            <span>ставок</span>
                            <a href="{{route('payment.win.info',['id'=>$data['id'],'exchange'=>true])}}">получить</a>
                            <div class="bonus__item">
                                <h5>+ подарок </h5>
                                <h4>{{$data['bonus']}} Бонусов </h4>
                            </div>
                            <span
                                style="margin: auto">На эти ставки Вы сможете  получить выгрывать еще больше призов !</span>
                            <img width="80px" src="{{asset('site/img/gifts.png')}}" alt="gifts">
                        </div>
                        <div class="bottom__item">
                            <p>СОВЕТ: получить Ставки и бонусы намного выгодней покупки товара </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
