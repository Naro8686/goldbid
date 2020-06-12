@extends('layouts.admin')
@section('content')

    @include('admin.includes.pages.dynamic_link')
    @push('js')
        <script>
            $(document).ready(function () {
                var getTextByEditor;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(document).on('click', 'button.btn-toggle', (e) => {
                    let id = $(e.currentTarget).attr('data-id');
                    let show = $(e.currentTarget).attr('aria-pressed') === 'true';
                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.pages.footer.linkOnOff')}}',
                        data: {
                            id: id,
                            show: show
                        },
                        success: () => {
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                });
                $(document).on('change', 'select[name="position"]', (e) => {
                    let _this = $(e.currentTarget);
                    let id = _this.attr('data-id');
                    let position = _this.val();
                    preloaderShow();
                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.pages.footer.linkPosition')}}',
                        data: {
                            id: id,
                            position: position
                        },
                        success: (data) => {
                            if (data.success) {
                                $("div#dynamic_link").replaceWith(data.html);
                            }
                            preloaderHide();
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                });
                $(document).on('change', 'select[name="float"]', (e) => {
                    let _this = $(e.currentTarget);
                    let id = _this.attr('data-id');
                    let float = _this.val();
                    preloaderShow();
                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.pages.footer.linkFloat')}}',
                        data: {
                            id: id,
                            float: float
                        },
                        success: (data) => {
                            if (data.success) {
                                $("div#dynamic_link").replaceWith(data.html);
                            }
                            preloaderHide();
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                });
                $('#exampleModal').on('show.bs.modal', function (event) {
                    let data = {};
                    let modal = $(this);
                    let button = $(event.relatedTarget);
                    data.type = button.data('type');
                    data.id = button.data('id');
                    data.social = button.data('social');
                    data.name = button.closest('tr').find('p.name').text();
                    data.link = button.closest('tr').find('a.link').attr('href');
                    data.title = button.data('metaTitle');
                    data.keywords = button.data('metaKeywords');
                    data.src = button.closest('tr').find('img.img-thumbnail').attr('src');
                    data.description = button.data('metaDescription');
                    data.content = button.data('metaContent');
                    let div = modal.find('.modal-body form .render-html').empty();
                    let input = renderModalItems(data);

                    div.append(input);
                    modal.find('.modal-title').text(`Link ${data.type}`);
                    modal.find('.modal-body form input[name="type"]').val(data.type);
                    modal.find('.modal-body form input[name="id"]').val(data.id);
                    modal.find('.modal-body form input[name="social"]').val(data.social);
                    if (!data.social && data.type !== 'delete') {
                        ClassicEditor
                            .create($('textarea#social-content')[0],{
                                image: {
                                    toolbar: [ 'imageTextAlternative' ]
                                }
                                // toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
                                // heading: {
                                //     options: [
                                //         { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                //         { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                //         { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                                //     ]
                                // }
                            })
                            .then(editor => {
                                getTextByEditor = editor;
                            })
                            .catch(error => {
                                console.error(error);
                            });

                    }
                });
                $(document).on('click', 'button.submit', function () {
                    $('textarea#social-content').val(getTextByEditor.getData());
                    let form = $('form#footer-crud');
                    let action = form.attr('action');
                    let data = new FormData(form[0]);
                    form.find('small.text-danger').remove();
                    form.find('.is-invalid').removeClass('is-invalid');
                    preloaderShow();
                    $.ajax({
                        type: 'POST',
                        url: action,
                        processData: false,
                        contentType: false,
                        data: data,
                        success: (data) => {
                            if (data.success) {
                                $("div#dynamic_link").replaceWith(data.html);
                            }
                            $('#exampleModal').modal("hide");
                            preloaderHide();
                        },
                        error: (response) => {
                            if (response.status === 422) {
                                let errors = response.responseJSON.errors;
                                Object.keys(errors).forEach(function (key) {
                                    let input = $(`form#footer-crud .render-html input[name="${key}"]`);
                                    input.addClass('is-invalid');
                                    input.after(`<small class="text-danger">${errors[key][0]}</small>`);
                                });
                            } else {
                                // window.location.reload();
                            }
                            preloaderHide();
                        }
                    });
                });

                $(document).on('change', '#upload', function () {
                    readURL($(this)[0]);
                });
            });
        </script>
    @endpush
@endsection
