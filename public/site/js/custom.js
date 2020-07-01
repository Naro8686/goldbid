$(document).ready(function () {
    $('.cookie__btn').on('click', function () {
        let btn = $(this);
        let agree = btn.data('agree');
        $.ajax({
            url: "/cookie-agree",
            type: "get",
            data: {
                agree: agree
            },
            success: function (result) {
                btn.closest('.agree_cookie').slideUp("slow");
            }
        });
    });
});
$(document).on('click','.notify__modal__btn__close', function () {
    let btn = $(this);
    let modal = btn.closest('.notify__modal');
    modal.toggleClass('close');
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
    } catch(e) {
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
