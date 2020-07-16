Echo.channel('goldbid_database_status-change').listen('StatusChangeEvent', (e) => {
    let status_change = e.data.status_change;
    if (status_change) {
        ChangeStatus();
    }
});
Echo.channel('goldbid_database_bet-auction').listen('BetEvent', (e) => {
    console.log(e)
});
