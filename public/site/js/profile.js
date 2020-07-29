const host = `${window.location.protocol}//${window.location.hostname}`
$(document).ready(function () {
    (function () {
        let uploader = document.createElement('input'),
            image = document.getElementById('img-result'),
            avatar = document.getElementById('avatar');
        uploader.type = 'file';
        uploader.accept = 'image/*';
        image.onclick = function () {
            uploader.click();
        }
        uploader.onchange = function () {
            let reader = new FileReader();
            let request = new FormData();
            reader.onload = function (evt) {
                image.classList.remove('no-image');
                image.style.backgroundImage = 'url(' + evt.target.result + ')';
                avatar.style.backgroundImage = 'url(' + evt.target.result + ')';
            }
            request.append('file', uploader.files[0]);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: `${host}/cabinet`,
                method: "POST",
                data: request,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.success === true) {
                        reader.readAsDataURL(uploader.files[0]);
                    }
                },
                error: function (err) {
                    window.location.reload();
                }
            })
        }
    })();
    $('#check__code').click(function () {
        let code = $(this).prev().val();
        let form = $('form#send__code_check');
        let input = form.find('input[name="code"]');
        if (code !== '' && input.val(code)) form.submit();
    });
    $('#copyButton').click(function () {
        copyToClipboard(document.getElementById("ref-link"));
    });
});

