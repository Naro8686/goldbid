
CKEDITOR.editorConfig = function (config) {
    config.removePlugins = 'Save,Iframe,easyimage, cloudservices';
    config.language = 'ru';
    config.removeButtons = 'Save,Iframe';
    config.filebrowserUploadUrl = "/admin/pages/dynamic-page/upload/image?type=Images";
};
