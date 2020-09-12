Echo.channel('goldbid_database_status-change')
    .listen('StatusChangeEvent', (e) => {
        if (Object.keys(e.data).length) {
            if (e.data.status_change)
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
