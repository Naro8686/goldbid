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
                //let auctionID = __this.closest('.card').data('auctionId');
                if (__this.hasClass('to__start')) __this.html('00:00:00');
                else __this.html('00:00');
                //arr.push(auctionID);
                //console.log(arr);
                //ChangeStatus(auctionID);

                //console.log(countdown,time);
                //ChangeStatus(__this.closest('.card').data('auctionId')).then(r => console.log(r));
                //console.log('old', __this.data('countdown'), __this.closest('.card').data('auctionId'));
                // if ($this.data('countdown') === 0) {
                //     if ($this.hasClass('to__start')) $this.html('00:00:00');
                //     else $this.html('00:00');
                //     setTimeout(function () {
                //         if (!$this.hasClass('to__start') && $this.data('countdown') === 0) {
                //             let card = $this.closest('.card');
                //             let homePage = card.closest('#home_page').length
                //             let winner = card.find('p.winner');
                //             if (homePage && winner.text().length === 0) {
                //                 let info = card.find('div.info');
                //                 let favorites = card.find('div.favorites ');
                //                 let div = card.find('div.btn.active');
                //                 let btn = div.children('button');
                //                 let text = btn.prev().text();
                //                 $('<div class="lenta not__win">Не состоялся</div>').insertAfter(info);
                //                 div.removeClass('active').addClass('not__win');
                //                 btn.text(text)
                //                 favorites.remove();
                //                 card.removeClass('active');
                //                 $this.remove();
                //             }
                //         }
                //     }, 1000);
                // }
            });
    });
}

function ChangeStatus(id) {
    let host = `${window.location.protocol}//${window.location.hostname}`
    let url = ((typeof id === 'number') ? `${host}/change-status/${id}` : `${host}/change-status`);
    let home_page = $('#home_page');
    let auction_page = $(`#auction_page[data-auction-id="${id}"]`);
    $.ajax({
        url: url,
        headers: {
            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
        },
        type: "POST",
        cache: false,
        datatype: "json",
        //async: true,
    }).done(function (data) {
        if (Object.keys(data).length) {
            if (home_page.length) {
                let auction = home_page.find(`[data-auction-id="${id}"]`);
                if (data.home_page) {
                    let html = $(data.home_page);
                    auction.replaceWith(html);
                    countdown(html);
                } else auction.remove();
            }
            if (auction_page.length) {
                if (data.auction_page) {
                    let html = $(data.auction_page);
                    auction_page.empty().html(html);
                    countdown(html);
                } else auction_page.remove();
            }
        }
    }).fail(function (xhr) {
        let data = xhr.responseJSON;
        if (xhr.status === 403 && Object.keys(data).length) {
            if (auction_page.length && data.auction_page) {
                let parent = auction_page.closest('div.card');
                parent.find('.slider-nav').remove();
                parent.find('.slider-for').html(`<img src='${URL}/site/img/settings/error.jpg' width='100%' alt='error'>`);
                auction_page.css("background-color", "#FF001A");
                let html = $(data.auction_page);
                auction_page.empty().html(html);
            }
            if (home_page.length && data.home_page) {
                let html = $(data.home_page);
                let auction = home_page.find(`[data-auction-id="${id}"]`);
                if (id !== null && auction.length) auction.replaceWith(html);
                else home_page.empty().html(html);
            }
        }
    });
}

Echo.channel('goldbid_database_status-change')
    .listen('StatusChangeEvent', (e) => {
        if (Object.keys(e.data).length && e.data.status_change && $(`[data-auction-id="${e.data.auction_id}"]`).length) {
            ChangeStatus((e.data.auction_id));
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
