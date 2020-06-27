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
    })
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
