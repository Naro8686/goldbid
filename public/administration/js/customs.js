$(document).ready(function () {
    preloaderHide();
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
            input += `<div class="thumbnail text-center"><img src="" class="img-fluid img-thumbnail m-2" alt="" id="imageResult"><div class="custom-file"><input type="file" class="custom-file-input" id="upload" name="image"><label class="custom-file-label" for="upload">Choose file</label></div></div>`;
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
            input += `<div class="thumbnail text-center"><img src="${data.src}" class="img-fluid img-thumbnail m-2" alt="" id="imageResult"><div class="custom-file"><input type="file" class="custom-file-input" id="upload" name="image" value=""><label class="custom-file-label" for="upload">Choose file</label></div></div>`;
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
        input += `<div class="form-group"><h2 class="text-center">Are you sure ?</h2></div>`;
    }
    return input;

}


/*  ==========================================
    SHOW UPLOADED IMAGE NAME
* ========================================== */
// var input = document.getElementById( 'upload' );
// var infoArea = document.getElementById( 'upload-label' );
//
// input.addEventListener( 'change', showFileName );
// function showFileName( event ) {
//     var input = event.srcElement;
//     var fileName = input.files[0].name;
//     infoArea.textContent = 'File name: ' + fileName;
// }