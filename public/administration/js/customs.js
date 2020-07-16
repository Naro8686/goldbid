$(document).ready(function () {
    phoneMask();
    preloaderHide();
    $(document).on('change', '#upload', function () {
        readURL($(this)[0]);
    });
    $(document).on('click', '*[data-target="#resourceModal"]', function (e) {
        let _this = $(this);
        let action = _this.data('action');
        let form = $(_this.data('target')).find('form#resource-delete');
        form.attr('action', action);
    });
    $.fn.modal.Constructor.prototype._enforceFocus = function () {
        let element = $(this._element);
        $(document).on('focusin.modal', function (e) {
            if (element[0] !== e.target && !element.has(e.target).length
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                element.focus()
            }
        })
    };
    if ($('textarea').hasClass('ck__textarea')) {
        $('textarea.ck__textarea').each(function (key, el) {
            window.editor = CKEDITOR
                .replace(el, {
                    customConfig: "/ckeditor/config.js",
                });
        });
    }
    $('.sidebar-brand').click(function (e) {
        e.preventDefault();
        let url = '/';
        if (!$(e.target).hasClass('logo'))
            url += 'admin';
        window.location.href = url;
    })
});

function preloader() {
    let preloader = '' +
        '<div class="preloader-content">' +
        '<div class="preloader">' +
        '<div class="📦"></div>' +
        '<div class="📦"></div>' +
        '<div class="📦"></div>' +
        '<div class="📦"></div>' +
        '<div class="📦"></div>' +
        '</div>' +
        '</div>';
    $('div.container-fluid').append(preloader);
}

function preloaderHide() {
    $('div.preloader-content').slideUp('slow');
}

function preloaderShow() {
    $('div.preloader-content').slideDown('fast');
}


function readURL(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $('#imageResult')
                .attr('src', e.target.result);
            $('label[for="upload"]').text(input.files[0].name);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function renderModalItems(data) {
    const INSERT = 'insert';
    const UPDATE = 'update';
    const DELETE = 'delete';
    let hostname = `${window.location.protocol}//${window.location.hostname}/`;
    let input = '';
    if (data.type === INSERT) {
        if (data.social) {
            hostname = '';
            input += `<div class="thumbnail text-center"><img src="" class="img-fluid img-thumbnail mb-2" alt="" id="imageResult"><div class="custom-file"><input type="file" class="custom-file-input" id="upload" name="image"><label class="custom-file-label" for="upload">Выберите файл</label></div></div>`;
        } else {
            input += `<div class="form-group"><label for="social-meta-title" class="col-form-label">Seo Title</label><input type="text" class="form-control" id="social-meta-title" name="title" value=""></div>`;
            input += `<div class="form-group"><label for="social-meta-keywords" class="col-form-label">Seo Keywords</label><input type="text" class="form-control" id="social-meta-keywords" name="keywords" value=""></div>`;
            input += `<div class="form-group"><label for="social-meta-description" class="col-form-label">Seo Description</label><input type="text" class="form-control" id="social-meta-description" name="description" value=""></div>`;
        }
        input += `<div class="form-group"><label for="social-name" class="col-form-label">Наименование</label><input type="text" class="form-control" id="social-name" name="name" value=""></div>`;
        input += `<div class="form-group"><label for="social-link" class="col-form-label">Ссылка</label><input type="text" class="form-control" id="social-link" name="link" value="${hostname}"></div>`;
        if (!data.social) {
            input += `<div class="form-group"><label for="social-content" class="col-form-label">Содержание страницы</label><textarea class="form-control" id="social-content" name="content"></textarea></div>`;
        }
    } else if (data.type === UPDATE) {
        if (data.social) {
            input += `<div class="thumbnail text-center"><img src="${data.src}" class="img-fluid img-thumbnail mb-2" alt="" id="imageResult"><div class="custom-file"><input type="file" class="custom-file-input" id="upload" name="image" value=""><label class="custom-file-label" for="upload">Выберите файл</label></div></div>`;
        } else {
            input += `<div class="form-group"><label for="social-meta-title" class="col-form-label">Seo Title</label><input type="text" class="form-control" id="social-meta-title" name="title" value="${data.title}"></div>`;
            input += `<div class="form-group"><label for="social-meta-keywords" class="col-form-label">Seo Keywords</label><input type="text" class="form-control" id="social-meta-keywords" name="keywords" value="${data.keywords}"></div>`;
            input += `<div class="form-group"><label for="social-meta-description" class="col-form-label">Seo Description</label><input type="text" class="form-control" id="social-meta-description" name="description" value="${data.description}"></div>`;
        }
        input += `<div class="form-group"><label for="social-name" class="col-form-label">Наименование</label><input type="text" class="form-control" id="social-name" name="name" value="${data.name}"></div>`;
        input += `<div class="form-group"><label for="social-link" class="col-form-label">Ссылка</label><input type="text" class="form-control" id="social-link" name="link" value="${data.link}"></div>`;
        if (!data.social) {
            input += `<div class="form-group"><label for="social-content" class="col-form-label">Содержание страницы</label><textarea class="form-control" id="social-content" name="content">${data.content}</textarea></div>`;
        }
    } else if (data.type === DELETE) {
        input += `<div class="form-group"><h2 class="text-center">Вы уверены ?</h2></div>`;
    }
    return input;
}

function oNoFF(action, data = {}, method = "GET") {
    if (method === "PUT") {
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
        success: (data) => {
            if (data)
                $(`#${data.id_name}`).html(data.change_info);
        },
        error: (error) => {
            console.log(error)
        }
    });
}

function phoneMask() {
    let phones = document.querySelectorAll('input.mask');
    [].forEach.call(phones, function (phone) {
        IMask(phone, {
            mask: '+{7}(000)000-00-00'
        });
    });
}


function imagesURL(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        let imagePreview = $(input).closest('.image-upload').find('.imagePreview');
        reader.onload = function (e) {
            imagePreview.css('background-image', 'url(' + e.target.result + ')');
            imagePreview.hide();
            imagePreview.fadeIn(650);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$("input.imageUpload").change(function () {
    imagesURL(this);
});
