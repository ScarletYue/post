document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#editor',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
    });
});
