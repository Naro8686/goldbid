Echo.channel('goldbid_database_status-change')
    .listen('StatusChangeEvent', (e) => {
        let status_change = e.data.status_change;
        let id = e.data.auction_id ?? null;
        if (status_change) {
            ChangeStatus(id);
        }
    });

Echo.channel('goldbid_database_bet-auction')
    .listen('BetEvent', (data) => {
        if (data.auction) {
            let auction = data.auction;
            let auction_id = auction.id;
            let auction_page = $(`[data-auction-id="${auction_id}"]`);
            if (auction_page.length) {
                let nickname = auction.nickname;
                let price = auction.price;
                let step_time = auction.step_time;
                let tr = auction.tr;
                let tbody = auction_page.find('table.scrolldown tbody')
                tbody.html($(tr));
                auction_page.find('.winner').text(nickname);
                auction_page.find('.price').text(price);
                console.log(step_time);
                countdown(auction_page, step_time);
                if (data.user) {
                    let user = data.user;
                    let user_id = user.id;
                    let user_page = $(`#user_${user_id}`);
                    if (user_page.length) {
                        user_page.find('.phpbalance').text(user.bet);
                        user_page.find('.phpbonus').text(user.bonus);
                        let user_auction_page = user_page.closest('body').find($(auction_page));
                        if (user_auction_page.length){
                            let elements = user_page.closest('body').find($(auction_page)).contents();
                            elements.find('.bet').text(user.auction_bet);
                            elements.find('.bonus').text(user.auction_bonus);
                            elements.find('.buy__now_price').text(user.full_price);
                            elements.find('.auto__bid_inp').val(user.auto_bid);
                            console.log(user.auto_bid);
                        }
                    }
                }
            }
        }
    });
