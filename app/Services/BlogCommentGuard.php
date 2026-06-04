<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCommentGuard
{
    /** @var list<string> */
    protected array $spamPhrases = [
        'buy now', 'click here', 'casino', 'viagra', 'crypto giveaway',
        'work from home', 'make money fast', 'seo service', 'backlink',
        'guest post', 'telegram', 'whatsapp me', 'loan approved',
    ];

    public function issueChallenge(): array
    {
        $a = random_int(2, 12);
        $b = random_int(2, 12);

        return [
            'a' => $a,
            'b' => $b,
            'answer' => $a + $b,
            'token' => (string) Str::uuid(),
        ];
    }

    public function storeChallenge(array $challenge): void
    {
        session([
            'blog_comment_challenge' => [
                'token' => $challenge['token'],
                'answer' => (int) $challenge['answer'],
                'expires_at' => now()->addMinutes(30)->timestamp,
            ],
        ]);
    }

    public function validate(Request $request): ?string
    {
        if (filled($request->input('kdr_extra_info'))) {
            return 'Your comment could not be submitted.';
        }

        if (filled($request->input('kdr_hp_confirm'))) {
            return 'Your comment could not be submitted.';
        }

        $started = (int) $request->input('kdr_form_started', 0);
        if ($started > 0 && (time() - $started) < 4) {
            return 'Please take a moment to read the article before commenting.';
        }

        $challenge = session('blog_comment_challenge');
        $token = $request->input('challenge_token');
        $answer = $request->input('challenge_answer');

        if (
            ! $challenge
            || ($challenge['token'] ?? '') !== $token
            || ($challenge['expires_at'] ?? 0) < time()
            || (int) $answer !== (int) ($challenge['answer'] ?? -1)
        ) {
            return 'Please answer the quick verification question correctly.';
        }

        $comment = trim((string) $request->input('comment', ''));
        $names = trim((string) $request->input('names', ''));

        if (strlen($comment) < 20) {
            return 'Please write a meaningful comment (at least 20 characters).';
        }

        if (strlen($comment) > 2000) {
            return 'Comment is too long (maximum 2000 characters).';
        }

        if (preg_match_all('/https?:\/\//i', $comment) > 1) {
            return 'Comments with multiple links are not allowed.';
        }

        if (preg_match('/[A-Z]{20,}/', $comment)) {
            return 'Please avoid excessive capital letters.';
        }

        $lower = Str::lower($comment.' '.$names);
        foreach ($this->spamPhrases as $phrase) {
            if (Str::contains($lower, $phrase)) {
                return 'Your comment was flagged as spam and was not saved.';
            }
        }

        if (! preg_match('/[\p{L}]/u', $names)) {
            return 'Please enter a valid name.';
        }

        return null;
    }

    public function clearChallenge(): void
    {
        session()->forget('blog_comment_challenge');
    }
}
