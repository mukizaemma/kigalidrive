@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
(function ($) {
    var toolbar = [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
    ];
    function bindSummernote($ta, placeholder, height) {
        if (!$ta.length || $ta.next('.note-editor').length) {
            return;
        }
        $ta.summernote({
            placeholder: placeholder,
            tabsize: 2,
            height: height || 200,
            toolbar: toolbar
        });
        $ta.closest('form').on('submit', function () {
            $ta.val($ta.summernote('code'));
        });
    }
    $(function () {
        bindSummernote($('#hotelDescription'), 'Property description', 220);
        bindSummernote($('#roomDescription'), 'Room description', 200);
    });
})(jQuery);
</script>
@endpush
