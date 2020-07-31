<div class="notify__modal">
    <div class="notify__item">
        <div class="notify__modal__body">
            <div class="win__info_container">
                <div class="image__container">
                    <p>{{$data['title']}}</p>
                    <img src="{{asset($data['image'])}}" alt="{{$data['alt']}}">
                </div>
                <div class="info__container">
                    <div class="info__container_absolute">
                        <span
                            style="width: 24px;height: 8px;color: #575656;font-size: 14px;font-weight: 400;;position: absolute;top: 110px;">или</span>
                        <div class="left__item">
                            <p>{{$data['price']}}</p>
                            <span style="font-size: 23px;font-weight: 400;padding: 5px;">рублей</span>
                            <a href="{{route('payment.auction.order',['id'=>$data['id'],'step'=>'1'])}}">оплатить</a>
                            <span style="margin:75px 0px 15px 0px;width: 120px;font-size: 14px;font-weight: 400;">После оплаты мы отправим Вам выигранный лот </span>
                            <img style="width: 54px;height: 61px;" src="{{asset('site/img/gift.png')}}" alt="gift">
                        </div>
                        <div class="right__item">
                            <p>{{$data['bet']}}</p>
                            <span style="font-size: 23px;font-weight: 400;padding: 5px;">ставок</span>
                            <a href="{{route('payment.win.info',['id'=>$data['id'],'exchange'=>true])}}">получить</a>
                            <div class="bonus__item">
                                <h2>+ {{$data['bonus']}}  </h2>
                                <h3>Бонусов </h3>
                            </div>
                            <span style="width: 110px;font-size: 14px;font-weight: 400;">
                                На эти ставки Вы сможете  выгрывать еще больше призов!</span>
                            <img style="width: 126px;height: 71px;" src="{{asset('site/img/gifts.png')}}" alt="gifts">
                        </div>
                        <div class="bottom__item">
                            <p>СОВЕТ: получить Ставки и Бонусы намного выгодней покупки товара </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
