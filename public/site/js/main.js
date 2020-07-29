$(document).ready(function () {
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
