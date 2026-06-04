@php
    $challenge = $commentChallenge ?? [];
@endphp
<div class="kdr-article-comments mt-5 pt-4 border-top" id="article-comments">
    <h3 class="h5 mb-3">Comments ({{ $commentsCount ?? 0 }})</h3>
    <p class="text-muted small">Comments are reviewed before they appear. Spam and promotional posts are removed.</p>

    @if(session('success'))
        <div class="alert alert-success py-2 small">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
    @endif

    <form action="{{ route('sendComment') }}" method="POST" class="kdr-comment-form mb-4" id="kdrCommentForm" autocomplete="off">
        @csrf
        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
        <input type="hidden" name="challenge_token" value="{{ $challenge['token'] ?? '' }}">
        <input type="hidden" name="kdr_form_started" id="kdrFormStarted" value="">

        {{-- Honeypots (bots often fill these; humans never see them) --}}
        <div class="kdr-hp-field" aria-hidden="true">
            <label for="kdr_extra_info">Leave empty</label>
            <input type="text" name="kdr_extra_info" id="kdr_extra_info" tabindex="-1" autocomplete="off">
        </div>
        <div class="kdr-hp-field" aria-hidden="true">
            <label for="kdr_hp_confirm">Do not fill</label>
            <input type="text" name="kdr_hp_confirm" id="kdr_hp_confirm" tabindex="-1" autocomplete="nope">
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="comment_name">Your name <span class="text-danger">*</span></label>
                <input type="text" name="names" id="comment_name" class="form-control" required maxlength="80"
                       value="{{ old('names') }}" autocomplete="name">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="comment_email">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="comment_email" class="form-control" required maxlength="120"
                       value="{{ old('email') }}" autocomplete="email">
            </div>
            <div class="col-12">
                <label class="form-label" for="comment_body">Your comment <span class="text-danger">*</span></label>
                <textarea name="comment" id="comment_body" class="form-control" rows="4" required minlength="20" maxlength="2000"
                          placeholder="Share your thoughts about this article (min. 20 characters)">{{ old('comment') }}</textarea>
            </div>
            @if(isset($challenge['a'], $challenge['b']))
            <div class="col-md-6">
                <label class="form-label" for="challenge_answer">Quick check: {{ $challenge['a'] }} + {{ $challenge['b'] }} = ? <span class="text-danger">*</span></label>
                <input type="number" name="challenge_answer" id="challenge_answer" class="form-control" required
                       inputmode="numeric" autocomplete="off" value="{{ old('challenge_answer') }}">
                <small class="text-muted">Helps us block automated spam.</small>
            </div>
            @endif
            <div class="col-12">
                <button type="submit" class="th-btn btn-kdr-primary btn-sm">Submit comment</button>
            </div>
        </div>
    </form>

    @forelse($comments ?? [] as $comment)
    <div class="kdr-comment-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between small text-muted mb-2">
            <strong class="text-dark">{{ $comment->names }}</strong>
            <span>{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <p class="mb-0">{{ $comment->comment }}</p>
    </div>
    @empty
    <p class="text-muted small">No approved comments yet. Be the first to share your thoughts.</p>
    @endforelse
</div>

@push('styles')
<style>
    .kdr-hp-field {
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var started = document.getElementById('kdrFormStarted');
    if (started) {
        started.value = Math.floor(Date.now() / 1000);
    }
});
</script>
@endpush
