const editors = {};

function createEditor(id, value) {
    ClassicEditor
        .create(document.getElementById(id), {
            toolbar: [
                'undo', 'redo',
                '|', 'heading',
                '|', 'bold', 'italic', 'link', 'blockquote',
                '|', 'numberedList', 'bulletedList', 'indent', 'outdent',
            ],
        })
        .then(editor => {
            editors[id] = editor;
            editor.setData(value ?? '');
        })
        .catch(error => {
            console.error(error);
        });
}

function createReadOnlyEditor(id, value) {
    ClassicEditor
        .create(document.getElementById(id), {
            toolbar: [],
        })
        .then(editor => {
            editor.enableReadOnlyMode(id);
            editor.setData(value ?? '');
        })
        .catch(error => {
            console.error(error);
        });
}

function addEditorDataToForm(form, id) {
    $(form).append(`<input type="hidden" name="${id}" value="${editors[id].getData()}" />`);
}
