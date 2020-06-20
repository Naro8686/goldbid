$(document).on('click', '.bid', function () {

    var id = $(this).attr('id');
    var name = $(this).attr('name');
    $.post("php/function.php", {id: id, name: name});
});

$(document).on('click', '.bid-btn', function () {
    if (tovarBuy != true) {
        var id = $(this).attr('id');
        var name = $(this).attr('name');
        $.post("php/function.php", {id: id, name: name});
    } else {
        alert("Вы не можете участвовать в аукционе");
    }
});

$(document).on('click', 'span[name=favorite]', function () {
    var id = $(this).attr('id');
    $.post("php/function.php", {favorite: id});
});
$(function () {
    $(".main .payment .pay").click(function (e) {
        e.preventDefault();
        $(".main .payment .pay").removeClass('active');
        $(this).addClass('active');
    })
});

$('.uncheck').click(function () {
    var value = $(this).attr("id");
    $(this).removeClass('uncheck');
    $('.check').removeClass('check').addClass('uncheck');
    $(this).addClass('check');
    $('[name = sum]').val(value * 10);
});

function get_auction() {
    $.ajax({
        url: "loader.php",
        type: "get",
        success: function (result) {
            $('.delete-margin').html(result);
        }
    })
}


function get_balance() {
    $.getJSON("php/balance.php").done(function (result) {
        $('.phpbalance').html(result[0]);
        $('.phpbonus').html(result[1]);
    });
}

function getInfoTov() {
    $.getJSON("loader_data.php",
        function (data) {
            for (i = 0; i < data.length; i++) {
                if (data[i].status == 1) {
                    $('[data-id=' + data[i].id + '] .timer').html(data[i].timer);
                    $('[data-id=' + data[i].id + '] .price').html(data[i].price);
                    $('[data-id=' + data[i].id + '] .username').html(data[i].winer);
                } else if (data[i].status == 0) {
                    $('[data-id=' + data[i].id + '] .timer').html("До начала " + data[i].timer);
                }
            }
        }
    );
}

$(document).ready(function () {
    //get_auction();
    //setInterval('get_auction()',1000);

    //get_balance();
    //setInterval('get_balance()',10000);

    phoneMask();
    $('form#register').on('submit', (e) => {
        e.preventDefault();
        let form = $(e.currentTarget);
        $('small.alert').remove();
        $('input[type="checkbox"]').removeClass('fail');
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'JSON',
            success: () => window.location.assign("/"),
            error: function (response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function (key) {
                        let input = $(`form#register input[name="${key}"]`);
                        if (input.attr('type') === 'checkbox')
                            input.addClass('fail');
                        else
                            input.after(`<small class="alert alert-danger">${errors[key][0]}</small>`);
                    });
                } else {
                    window.location.reload();
                }
            }
        });
    });


    $('form#login').on('submit', (e) => {
        e.preventDefault();
        let form = $(e.currentTarget);
        $('small.alert').remove();
        $('input[type="checkbox"]').removeClass('fail');
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'JSON',
            success: (data) => {
                if (data.auth) window.location.assign(data.intended);
            },
            error: function (response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function (key) {
                        let input = $(`form#login input[name="${key}"]`);
                        if (input.attr('type') === 'checkbox')
                            input.addClass('fail');
                        else
                            input.after(`<small class="alert alert-danger">${errors[key][0]}</small>`);
                    });
                } else {
                    window.location.reload();
                }
            }
        });
    });

});

function phoneMask() {
    let phones = document.querySelectorAll('input[name="phone"]');
    [].forEach.call(phones, function (phone) {
        IMask(phone, {
            mask: '+{7}(000)000-00-00'
        });
    });
}
