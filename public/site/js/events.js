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
                countdown(auction_page, step_time);
                if (data.user) {
                    let user = data.user;
                    let user_id = user.id;
                    let user_page = $(`#user_${user_id}`);
                    if (user_page.length) {
                        user_page.find('.phpbalance').text(user.bet);
                        user_page.find('.phpbonus').text(user.bonus);

                        console.log(user_page.closest('body').find(auction_page));
                        user_page.closest('body').find(auction_page).children('.bet').text(user.auction_bet);
                        user_page.closest('body').find(auction_page).children('.bonus').text(user.auction_bonus);
                        user_page.closest('body').find(auction_page).children('.buy__now_price').text(user.full_price);
                        user_page.closest('body').find(auction_page).children('.auto__bid_inp').val(user.auto_bid);

                    }
                }
            }
        }
    });
