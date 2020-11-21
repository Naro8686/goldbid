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
$(document).on('click', '.btn.active > button:not(.disabled),.inf__active > button:not(.disabled)', function (e) {
    let parent = $(this).closest('div[data-auction-id]');
    let auction_id = parent.attr('data-auction-id');
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
    //let home_page = $('#home_page');
    $.post(`${URL}/${auction_id}/add-favorite`, function () {
        favorite.toggleClass('active');
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

function preload() {
    let preload = document.createElement('div');
    let loader = document.createElement('div');
    let div = document.createElement('div');
    preload.className = 'preload__container';
    loader.className = 'loader';
    loader.appendChild(div);
    preload.appendChild(loader);
    $('#home_page').empty().html(preload);
}

function loadAuctions(page) {
    let container = $("#home_page");
    if (isNaN(page) || page <= 0) return false;
    $.ajax({
        url: `?page=${page}`,
        type: "GET",
        cache: false,
        datatype: "html",
        beforeSend: preload,
    }).done((data) => {
        if (!data.error && data.html) {
            let html = $(data.html);
            container.empty().html(html);
            location.hash = page;
            countdown(html);
        }
    }).fail((jqXHR, ajaxOptions, thrownError) => {
        console.log(jqXHR, ajaxOptions, thrownError);
    });
}
