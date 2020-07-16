const URL = `${window.location.protocol}//${window.location.hostname}`;
$(document).ready(function () {
    countdown();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
        }
    });
    $('.slaider').slick({
        autoplay: true,
        autoplaySpeed: 3000
    });
    $('.auction__slider').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 5000
    });
    $('.cookie__btn').on('click', function () {
        let btn = $(this);
        let agree = btn.data('agree');
        $.get(`${URL}/cookie-agree`, {agree: agree}, function (result) {
            btn.closest('.agree_cookie').slideUp("slow");
        });
    });
});
$(document).on('click', '.btn.active button', function (e) {
    let auction_id = $(this).closest('div[data-auction-id]').attr('data-auction-id');
    if (auction_id)
        $.get(`${URL}/bet/${auction_id}`, (data) => {
            if (data){
                $('.balance span.phpbalance').html(data.bet);
                $('.balance span.phpbonus').html(data.bonus);
            }
        });
})
$(document).on('click', '.notify__modal__btn__close', function () {
    let btn = $(this);
    let modal = btn.closest('.notify__modal');
    modal.toggleClass('close');
});
$(document).on('click', '.favorites', function (e) {
    let favorite = $(this).children();
    let auction_id = favorite.closest('.card').attr('data-auction-id');
    let container = $('.delete-margin');
    $.post(`${URL}/${auction_id}/add-favorite`, function (data) {
        favorite.toggleClass('active');
        container.html(data);
        countdown();
    });

});

function oNoFF(action, data = {}, method = "GET") {
    if (method === "PUT" || method === "DELETE") {
        data._method = method;
        method = "POST";
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: method,
        url: action,
        data: data,
        success: () => {
        },
        error: (error) => {
            console.log(error)
        }
    });
}

function copyToClipboard(elem) {
    let targetId = "_hiddenCopyText_";
    let isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    let origSelectionStart, origSelectionEnd;
    let target;
    if (isInput) {
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        target = document.getElementById(targetId);
        if (!target) {
            let target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    let currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    let succeed;
    try {
        succeed = document.execCommand("copy");
    } catch (e) {
        succeed = false;
    }
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        target.textContent = "";
    }
    return succeed;
}

function countdown() {
    $('[data-countdown]').each(function () {
        let $this = $(this), seconds = $(this).data('countdown');
        let time = new Date();
        time.setSeconds(time.getSeconds() + seconds);
        $this.countdown(time)
            .on('update.countdown', (event) => {
                let H = event.offset.totalDays * 24 + event.offset.hours;
                if (H < 10) H = `0${H}`;
                if ($this.hasClass('to__start'))
                    $this.html(event.strftime(`${H}:%M:%S`));
                else
                    $this.html(event.strftime('%M:%S'));
            })
            .on('finish.countdown', (event) => {
                $this.html('...');
            });
    });
}

function ChangeStatus() {
    let container = $('.delete-margin');
    $.post(`${URL}/change-status`, function (data) {
        container.html(data);
        countdown();
    });
}

