<template>
    <div class="card">
        <div class="left" style="overflow: hidden;">
            <div v-if="auction.error">
                <img src="/site/img/settings/error.jpg" width="100%" alt="error">
            </div>
            <template v-else>
                <div class="slider-for">
                    <div v-for="image in auction.images">
                        <img :src="`/${image.img}`" class="slide-img" :alt="image.alt">
                    </div>
                </div>
                <div class="slider-nav">
                    <div v-for="image in auction.images">
                        <img :src="`/${image.img}`" class="slide-img" :alt="image.alt">
                    </div>
                </div>
            </template>
        </div>
        <div id="auction_page" :data-auction-id="auction.id" class="dashboard"
             :style="[auction.error ? {'background-color': '#FF001A'} : {'background': '#167DB8'}]">
            <template v-if="auction.error">
                <h1 style="text-align: center">
                    АУКЦИОН ЗАБЛОКИРОВАН
                </h1>
                <div class="error-box">
                    <h3>Злоумышленники пытались воздействовать на ход игры данного аукциона.</h3>
                    <div>
                        <h4>Вами поставлено {{ auction.bet }} Ставок и {{ auction.bonus }} Бонусов</h4>
                        <h3> Все Ставки и Бонусы будут возвращены.</h3>
                    </div>
                    <h3>Приносим извинения за неудобства </h3>
                </div>
            </template>
            <template v-else>
                <p class="title">
                    {{ auction.title }}
                </p>
                <div class="info">
                    <div class="circl">
                        <p title="Время прибавляемое к таймеру после ставки">
                            {{ auction.bid_seconds }}
                            <br><span>сек</span>
                        </p>
                    </div>
                    <div class="circl">
                        <p title="Шаг ставки">{{ auction.step_price_info }}<br><span>коп</span></p>
                    </div>

                    <div v-if="auction.exchange" class="circl">
                        <img title='Возможнось получить вместо выигранного товара "ставки"'
                             src="/site/img/reload-white.png" alt="">
                    </div>

                    <div v-if="auction.buy_now" class="circl">
                        <a :class="{ 'my___win' : (auction.my_win && auction.exchange)}"
                           :data-id="auction.id"
                           :href="`/payment/${auction.id}/order?step=1`">
                            <img style="padding-left: 2px" :title="`Купить сейчас за ${auction.full_price} руб`"
                                 src="/site/img/korzina-white.png"
                                 alt="">
                        </a>
                    </div>
                </div>
                <div v-if="(auction.status === 1)" class="inf__pending">
                    <p> Аукцион начнется через</p>
                    <span class="to__start countdown"
                          :data-countdown="auction.start"></span>
                </div>

                <template v-if="(auction.status === 2)">
                    <p class="winner">{{ auction.winner }}</p>
                    <div class="inf__active">
                        <span class="price" :key="auction.my_win">{{ auction.price }} руб.</span>
                        <span class="countdown" :data-countdown="auction.step_time"></span>
                        <button :class="{ 'disabled' : (auction.autoBid !== null && parseInt(auction.autoBid) > 0)}">
                            Ставка
                        </button>
                    </div>
                    <!-- Сюда вставляется автоставки -->
                    <form class="auto__bid" method="POST" :action="`/${auction.id}/auto_bid`">
                        <input type="hidden" name="_token" :value="csrf">
                        <input :key="auction.id" class="auto__bid_inp" min="0" type="number" name="count"
                               :value="auction.autoBid"
                               placeholder="введите ставку">
                        <button type="submit" class="auto__bid_btn">
                            автоставка
                        </button>
                    </form>
                </template>
                <template v-if="(auction.status === 3 || auction.status === 4)">
                    <p v-if="(auction.winner === null)" class="winner">Не состоялся</p>
                    <template v-else>
                        <div class="inf__finish">
                            <p class="winner">Победитель: {{ auction.winner }}</p>
                            <p class="price__for_winner">Цена для победителя аукциона</p>
                            <p class="price" >{{ auction.price }} руб</p>
                        </div>
                        <div v-if="auction.my_win" class="btn win">
                            <a :class="{ 'my___win' : (auction.my_win && auction.exchange)}"
                               :data-id="auction.id"
                               :href="`/payment/${auction.id}/order?step=1`">Оформить заказ</a>
                        </div>
                    </template>
                </template>
                <template v-if="(auction.buy_now && !auction.my_win)">
                    <div
                        class="buy__now"
                        :class="{'mt-100': (auction.status === 1),  'mt-90': (auction.status === 3 && !auction.my_win)}">
                        <span class="buy__now_price">{{ auction.full_price }} руб</span>
                        <a :href="`/payment/${auction.id}/order?step=1`"
                           class="buy__now_btn">
                            купить сейчас
                        </a>
                    </div>
                    <p class="info__text" :style="[auction.status === 3 ? {'margin': '0'} : {'background': 'none'}]">
                        Цена с учетом сделанных Вами ставок</p>
                </template>
                <div class="info__my__bid">
                    <div class="item">
                        <span>Поставлено Ставок</span><span class="bet">{{ auction.bet }}</span>
                    </div>
                    <div class="item">
                        <span> Поставлено Бонусов</span><span
                        class="bonus">{{ auction.bonus }}</span>
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
                        <tr v-for="bid in auction.bids">
                            <td>{{ bid.price }}</td>
                            <td>{{ bid.nickname }}</td>
                            <td>{{ bid.created_at }}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </template>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        auction: {
            type: Object,
            required: true
        },
        csrf: {
            type: String,
            required: true
        }
    },
    mounted() {
        if (this.auction.status === 1 || this.auction.status === 2) countdown($(this.$el))
        $('.slider-for').slick({
            slidesToShow: 1,
            asNavFor: '.slider-nav',
            autoplay: true,
            autoplaySpeed: 5000
        });
        $('.slider-nav').slick({
            slidesToShow: 4,
            asNavFor: '.slider-for',
            focusOnSelect: true,
        });
    },
}
</script>
<style>
.inf__pending {
    line-height: 30px;
}
</style>
