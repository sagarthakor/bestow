$('.quill-editor').each(function(i, el) {
    var el = $(this), id = 'quilleditor-' + i, val = el.val(), editor_height = 200;
    var div = $('<div/>').attr('id', id).css('height', editor_height + 'px').html(val);
    el.addClass('d-none');
    el.parent().append(div);

    var quill = new Quill('#' + id, {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'], [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }], [{ 'direction': 'rtl' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }], [{ 'align': [] }], ['clean']
            ]
        },
        theme: 'snow'
    });
    quill.on('text-change', function() {
        el.html(quill.getText());
    });
});
