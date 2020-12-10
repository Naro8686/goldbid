function countdown(element, timer = null) {
    element.find('[data-countdown]').each(function (e, v) {
        let __this = $(this), seconds = ((timer !== null) ? timer : __this.data('countdown'));
        let time = new Date();
        let countdown = time.setSeconds(time.getSeconds() + seconds);
        __this.countdown(countdown)
            .on('update.countdown', (event) => {
                let H = (event.offset.totalDays * 24 + event.offset.hours);
                if (H < 10) H = `0${H}`;
                if (__this.hasClass('to__start'))
                    __this.html(event.strftime(`${H}:%M:%S`));
                else
                    __this.html(event.strftime('%M:%S'));
            })
            .on('finish.countdown', () => {
                if (__this.hasClass('to__start')) __this.html('00:00:00');
                else __this.html('00:00');
            });
    });
}

Echo.channel('goldbid_database_status-change')
    .listen('StatusChangeEvent', (data) => {
        if (Object.keys(data).length) {
            let home_page = $(`#home_page [data-auction-id="${data.id}"]`);
            let auction_page = $(`#auction_page[data-auction-id="${data.id}"]`);
            let user = $(`#username`);
            let full_price = $(`.buy__now_price`);
            data.my_win = ((data.status === 3 || data.status === 4) && user.length && data.winner !== null) ? ($.trim(user.text()) === data.winner) : false;
            data.error = (data.my_win && data.status === 4);
            data.favorite = (home_page.find('.favorites>span.active').length !== 0);
            data.bet = auction_page.find('.info__my__bid span.bet').length ? parseInt(auction_page.find('.info__my__bid span.bet').text()) : data.bet;
            data.bonus = auction_page.find('.info__my__bid span.bonus').length ? parseInt(auction_page.find('.info__my__bid span.bonus').text()) : data.bonus;
            data.full_price = (full_price.length && !data.my_win) ? $.trim(full_price.text()).replace(/ руб/gi, '') : data.full_price;

            if (home_page.length) {
                new Vue({
                    el: `[data-auction-id="${data.id}"]`,
                    template: '<auction-home v-bind:auction="auction"/>',
                    data: {
                        auction: data
                    },
                });
            }
            if (auction_page.length) {
                new Vue({
                    el: '.card',
                    template: '<auction-page v-bind:auction="auction" v-bind:csrf="csrf"/>',
                    data: {
                        auction: data,
                        csrf: $('meta[name="csrf-token"]').attr('content')
                    },
                });
            }

        }
    });

Echo.channel('goldbid_database_bet-auction')
    .listen('BetEvent', (data) => {
        let auction_div = null;
        if (Object.keys(data.auction).length) {
            let auction = data.auction;
            let auction_page = $(`[data-auction-id="${auction.id}"]`);
            if (auction_page.length) {
                auction_div = auction_page;
                let winner = auction_page.find('.winner');
                let price = auction_page.find('.price');
                let step_time = auction.step_time;
                let tr = auction.tr;
                let tbody = auction_page.find('table.scrolldown tbody')
                if (tbody.length) tbody.html($(tr));
                winner.text(auction.nickname);
                price.text(auction.price);
                countdown(auction_page, step_time);
            }
        }
        if (Object.keys(data.user).length) {
            let user = data.user;
            let user_page = $(`#user_${user.id}`);
            if (user_page.length) {
                user_page.find('.phpbalance').text(user.bet);
                user_page.find('.phpbonus').text(user.bonus);
                if (auction_div !== null) {
                    let user_auction_page = user_page.closest('body').find($(auction_div));
                    if (user_auction_page.length) {
                        let elements = user_page.closest('body').find($(auction_div)).contents();
                        if (elements.find('.bet').length)
                            elements.find('.bet').text(user.auction_bet);
                        if (elements.find('.bonus').length)
                            elements.find('.bonus').text(user.auction_bonus);
                        if (elements.find('.buy__now_price').length)
                            elements.find('.buy__now_price').text(user.full_price);
                        if (elements.find('.auto__bid_inp').length)
                            elements.find('.auto__bid_inp').val(user.auto_bid);
                        if (!user.auto_bid)
                            elements.find('button.disabled').removeClass('disabled');
                    }
                }
            }
        }
    });
