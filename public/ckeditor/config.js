
CKEDITOR.editorConfig = function (config) {
    config.removePlugins = 'image,Save.Iframe';
    config.language = 'ru';
    config.removeButtons = 'Save,Iframe';
    config.filebrowserUploadUrl = "/admin/pages/dynamic-page/upload/image?type=Images";
};
