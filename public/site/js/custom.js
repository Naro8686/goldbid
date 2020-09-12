const URL = `${window.location.protocol}//${window.location.hostname}`;
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
        }
    });
    $('.slaider').slick({
        autoplay: true,
        autoplaySpeed: 3000
    });

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
        //slidesToScroll: 1,

    });

    $('.cookie__btn').on('click', function () {
        let btn = $(this);
        let agree = btn.data('agree');
        $.get(`${URL}/cookie-agree`, {agree: agree}, function (result) {
            btn.closest('.agree_cookie').slideUp("slow");
        });
    });
});
$(document).on('click', '.btn.active,.inf__active button', function (e) {
    let auction_id = $(this).closest('div[data-auction-id]').attr('data-auction-id');
    if (auction_id)
        $.get(`${URL}/bet/${auction_id}`, (data) => {
            if (data) $('.response').empty().html(data);
        });
});
$(document).mouseup(function (e) {
    if ($(e.target).closest(".notify__item").length === 0) {
        let modal = $('.notify__modal');
        modal.toggleClass('close');
        modal.closest('.response').empty();
    }
});
$(document).on('click', '.notify__modal__btn__close', function () {
    let btn = $(this);
    let modal = btn.closest('.notify__modal');
    modal.toggleClass('close');
    modal.closest('.response').empty();
});
$(document).on('click', '.my___win', function (e) {
    e.preventDefault();
    let auction_id = $(this).data('id');
    $.get(`${URL}/payment/${auction_id}/win-info`, function (data) {
        $('.response').empty().html(data);
    });
});

$(document).on('click', '.favorites', function () {
    if ($(this).hasClass('top')) return false;
    let favorite = $(this).children();
    let auction_id = favorite.closest('.card').attr('data-auction-id');
    let home_page = $('#home_page');

    $.post(`${URL}/${auction_id}/add-favorite`, function (data) {
        favorite.toggleClass('active');
        //home_page.empty().html(data);
        //countdown(home_page);
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

function countdown(element, timer = null) {

    element.find('[data-countdown]').each(function (e, v) {
        let $this = $(this), seconds = (timer !== null) ? timer : $this.data('countdown');
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
                else $this.html('00:00');
            });
    });
}

function ChangeStatus(id = null) {
    let url = ((id !== null) ? `${URL}/change-status/${id}` : `${URL}/change-status`);
    let home_page = $('#home_page');
    let auction_page = $(`#auction_page[data-auction-id="${id}"]`);
    let auction = home_page.find(`div.card[data-auction-id="${id}"]`);
    $.post(url, function (data) {
        if (Object.keys(data).length) {
            if (data.home_page && home_page.length) {
                let html = $(data.home_page);
                if (id !== null) {
                    if (auction.length) auction.replaceWith(html);
                    //else home_page.append(html);
                } else home_page.html(html);
                let sec = html.find('[data-countdown]').data('countdown');
                sec ? countdown(html, sec) : false;
            }
            if (data.auction_page && auction_page.length) {
                let html = $(data.auction_page);
                auction_page.html(html);
                let sec = html.find('[data-countdown]').data('countdown');
                countdown(html, (sec ?? null));
            }
        }
    });
}

