<style>
    .note-editor.note-frame {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    .note-editor .note-toolbar {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    .note-editable {
        min-height: 140px;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .note-editable ul,
    .note-editable ol {
        padding-left: 1.25rem;
    }
    textarea.summernote[data-summernote-synced],
    textarea.summernote + .note-editor + textarea {
        /* summernote hides original textarea */
    }
</style>
