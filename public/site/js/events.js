function countdown(element, timer = null) {
    element.find('[data-countdown]').each(function (e, v) {
        let $this = $(this), seconds = ((timer !== null) ? timer : $this.data('countdown'));
        let time = new Date();
        let countdown = time.setSeconds(time.getSeconds() + seconds);
        $this.countdown(countdown)
            .on('update.countdown', (event) => {
                let H = (event.offset.totalDays * 24 + event.offset.hours);
                if (H < 10) H = `0${H}`;
                if ($this.hasClass('to__start'))
                    $this.html(event.strftime(`${H}:%M:%S`));
                else
                    $this.html(event.strftime('%M:%S'));
            })
            .on('finish.countdown', () => {
                if ($this.hasClass('to__start')) $this.html('00:00:00');
                else {
                    let card = $this.closest('.card');
                    let homePage = card.closest('#home_page').length
                    let winner = card.find('p.winner');
                    if (homePage && winner.text().length===0) {
                        let info = card.find('div.info');
                        let favorites = card.find('div.favorites ');
                        let div = card.find('div.btn.active');
                        let btn = div.children('button');
                        let text = btn.prev().text();
                        $('<div class="lenta not__win">Не состоялся</div>').insertAfter(info);
                        div.removeClass('active').addClass('not__win');
                        btn.text(text)
                        favorites.remove();
                        card.removeClass('active');
                        $this.remove();
                    }
                    $this.html('00:00')
                }
            });
    });
}

function ChangeStatus(id = null) {
    let host = `${window.location.protocol}//${window.location.hostname}`
    let url = ((id !== null) ? `${host}/change-status/${id}` : `${host}/change-status`);
    let home_page = $('#home_page');
    let auction_page = $(`#auction_page[data-auction-id="${id}"]`);
    $.post(url).done(function (data) {
        if (Object.keys(data).length) {
            if (home_page.length && data.home_page) {
                let html = $(data.home_page);
                let auction = home_page.find(`[data-auction-id="${id}"]`);
                auction.replaceWith(html);
                //if (id !== null) {
                //if (auction.length)
                //auction.replaceWith(html);
                //else home_page.append(html);
                //} else home_page.html(html);
                countdown(html);
            }
            if (auction_page.length && data.auction_page) {
                let html = $(data.auction_page);
                auction_page.html(html);
                countdown(html);
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
                auction_page.html(html);
            }
            if (home_page.length && data.home_page) {
                let html = $(data.home_page);
                if (id !== null && auction.length) auction.replaceWith(html);
                else home_page.html(html);
            }
        }
    });
}

Echo.channel('goldbid_database_status-change')
    .listen('StatusChangeEvent', (e) => {
        if (Object.keys(e.data).length && e.data.status_change) {
            ChangeStatus((e.data.auction_id ?? null));
        }
    });

Echo.channel('goldbid_database_bet-auction')
    .listen('BetEvent', (data) => {
        if (Object.keys(data.auction).length) {
            let auction = data.auction;
            let auction_page = $(`[data-auction-id="${auction.id}"]`);
            if (auction_page.length) {
                let nickname = auction.nickname;
                let price = auction.price;
                let step_time = auction.step_time;
                let tr = auction.tr;
                let tbody = auction_page.find('table.scrolldown tbody')
                if (tbody.length) tbody.html($(tr));
                auction_page.find('.winner').text(nickname);
                auction_page.find('.price').text(price);
                countdown(auction_page, step_time);
                if (Object.keys(data.user).length) {
                    let user = data.user;
                    let user_page = $(`#user_${user.id}`);
                    if (user_page.length) {
                        user_page.find('.phpbalance').text(user.bet);
                        user_page.find('.phpbonus').text(user.bonus);
                        let user_auction_page = user_page.closest('body').find($(auction_page));
                        if (user_auction_page.length) {
                            let elements = user_page.closest('body').find($(auction_page)).contents();
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
        }
    });
