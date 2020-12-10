<template>
    <div class="card" :data-auction-id="auction.id" :class="{ 'active' : auction.status === 2}">
        <div v-if="(auction.status === 2 || auction.status === 1)" class="favorites" :class="{ 'top' : auction.top}">
            <span :class="{ 'active' : (auction.favorite || auction.top)}"></span>
        </div>
        <a :href="`/${auction.id}/auction`">
            <img class="product-img"
                 :src="(auction.my_win && auction.status === 4 && auction.winner !== null) ? '/site/img/settings/error.jpg' : `/${auction.images[0].img}`"
                 :alt="(auction.my_win && auction.status === 4 && auction.winner !== null) ? 'error' : auction.images[0].alt">
        </a>
        <div class="name">
            <a :title="auction.title"
               :href="`/${auction.id}/auction`">{{ auction.title }}</a>
        </div>
        <div class="short__desc">
            <span>{{ auction.short_desc }}</span>
        </div>
        <div class="info">
            <div class="con-tooltip top">
                <div class="circl">
                    <p title="Время прибавляемое к таймеру после ставки">{{ auction.bid_seconds }}<br>
                        <span>сек</span>
                    </p>
                </div>
                <div class="tooltip first">
                    <p>Время прибавляемое к таймеру после ставки</p>
                </div>
            </div>
            <div class="con-tooltip top">
                <div class="circl ">
                    <p title="Шаг ставки">{{ auction.step_price_info }}<br><span>коп</span></p>
                </div>
                <div class="tooltip second">
                    <p>Шаг ставки</p>
                </div>
            </div>
            <div v-if="auction.exchange" class="con-tooltip top">
                <div class="circl">
                    <img title="Возможнось получить вместо выигранного товара &quot;ставки&quot;"
                         src="/site/img/arrow_black.png" alt="image">
                </div>

                <div class="tooltip three">
                    <p>Возможнось получить вместо выигранного товара "ставки"</p>
                </div>
            </div>
            <div v-if="auction.buy_now" class="con-tooltip top">
                <a :class="{ 'my___win' : (auction.my_win && auction.exchange)}"
                   :data-id="auction.id"
                   :href="`/payment/${auction.id}/order?step=1`">
                    <div class="circl">
                        <img :title="`Купить сейчас за ${auction.full_price} руб`"
                             src="/site/img/if_business_finance_money-05_2784238.png"
                             alt="image">
                    </div>
                </a>
            </div>
        </div>

        <div v-if="(auction.status === 1)" class="inf">
            <p class="timer">До начала <span class="to__start" :data-countdown="auction.start"></span></p>
        </div>
        <div v-if="(auction.status === 1)" class="btn">
            <span class="price">{{ auction.start_price }} руб</span>
            <button>Скоро</button>
        </div>


        <div v-if="(auction.status === 2)" class="inf">
            <p class="winner">{{ auction.winner }}</p>
            <p class="timer">
                <span :data-countdown="auction.step_time"></span>
            </p>
        </div>
        <div v-if="(auction.status === 2)" class="btn active">
            <span class="price">{{ auction.price }} руб</span>
            <button :class="{ 'disabled' : (auction.autoBid !== null && parseInt(auction.autoBid) > 0)}">Ставка</button>
        </div>
        <template v-if="(auction.status === 3 || auction.status === 4)">
            <template v-if="(auction.winner === null)">
                <div class="lenta not__win">Не состоялся</div>
                <div class="inf"></div>
                <div class="btn not__win">
                    <span class="price">{{ auction.price }} руб</span>
                    <button>{{ auction.price }} руб</button>
                </div>
            </template>
            <template v-else>
                <div class="inf">
                    <p v-if="(auction.status === 3 || auction.status === 4)" class="winner">{{ auction.winner }}</p>
                    <p v-if="(auction.status === 3 || auction.status === 4)" class="price">{{ auction.price }} руб</p>
                </div>
                <template v-if="(!auction.my_win || auction.ordered)">
                    <div class="lenta close">ЗАВЕРШЕН</div>
                    <div class="btn close">
                        <span class="price">{{ auction.price }} руб</span>
                        <button>Аукцион закрыт</button>
                    </div>
                </template>
                <template v-else>
                    <div v-if="(auction.status === 4)" class="btn error">
                        <button>ОШИБКА</button>
                    </div>
                    <div v-else class="btn win">
                        <span class="price">{{ auction.price }} руб</span>
                        <a :data-id="auction.id"
                           :href="`/payment/${auction.id}/order?step=1`"
                           :class="{ 'my___win' : (auction.my_win && auction.exchange)}">Оформить заказ</a>
                    </div>
                </template>
            </template>
        </template>
    </div>
</template>
<script>
export default {
    props: {
        auction: {
            type: Object,
            required: true
        },
    },
    mounted() {
        if (this.auction.status === 1 || this.auction.status === 2) countdown($(this.$el))
    }
}
</script>
