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
