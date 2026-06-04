<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleBusinessReviewService
{
    public function extractPlaceId(?string $url): ?string
    {
        if (!$url = trim((string) $url)) {
            return null;
        }

        if (preg_match('/[?&]place_id=([A-Za-z0-9_-]+)/', $url, $m)) {
            return $m[1];
        }

        if (preg_match('/!1s(ChIJ[A-Za-z0-9_-]+)/', $url, $m)) {
            return urldecode($m[1]);
        }

        if (preg_match('/\/place\/[^/]+\/data=.*!1s(ChIJ[A-Za-z0-9_-]+)/', $url, $m)) {
            return urldecode($m[1]);
        }

        if (preg_match('/\/(ChIJ[A-Za-z0-9_-]+)/', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public function profileUrl(?Setting $setting = null): ?string
    {
        $setting ??= Setting::first();
        if (!$setting) {
            return null;
        }

        $url = trim((string) ($setting->google_business_url ?? ''));
        if ($url !== '') {
            return $url;
        }

        $placeId = trim((string) ($setting->google_place_id ?? ''));
        if ($placeId !== '') {
            return 'https://www.google.com/maps/search/?api=1&query=Google&query_place_id=' . urlencode($placeId);
        }

        return null;
    }

    public function writeReviewUrl(?Setting $setting = null): ?string
    {
        $setting ??= Setting::first();
        if (!$setting) {
            return null;
        }

        $placeId = trim((string) ($setting->google_place_id ?? ''));
        if ($placeId === '') {
            $placeId = $this->extractPlaceId($setting->google_business_url) ?? '';
        }

        if ($placeId !== '') {
            return 'https://search.google.com/local/writereview?placeid=' . urlencode($placeId);
        }

        $profile = $this->profileUrl($setting);
        if ($profile && Str::contains($profile, 'g.page')) {
            return rtrim($profile, '/') . '/review';
        }

        return $profile;
    }

    /**
     * @return array{
     *   configured: bool,
     *   profile_url: ?string,
     *   write_review_url: ?string,
     *   rating: ?float,
     *   total: ?int,
     *   reviews: list<array{author_name: string, author_photo: ?string, rating: int, text: string, relative_time: string}>,
     *   error: ?string
     * }
     */
    public function getData(?Setting $setting = null): array
    {
        $setting ??= Setting::first();

        $profileUrl = $this->profileUrl($setting);
        $writeUrl = $this->writeReviewUrl($setting);
        $configured = (bool) ($profileUrl || $writeUrl);

        $empty = [
            'configured' => $configured,
            'profile_url' => $profileUrl,
            'write_review_url' => $writeUrl,
            'rating' => $setting?->google_rating ? (float) $setting->google_rating : null,
            'total' => $setting?->google_review_count ? (int) $setting->google_review_count : null,
            'reviews' => [],
            'error' => null,
        ];

        if (!$setting) {
            return array_merge($empty, ['configured' => false, 'error' => 'Settings not found.']);
        }

        $placeId = trim((string) ($setting->google_place_id ?? ''));
        if ($placeId === '') {
            $placeId = $this->extractPlaceId($setting->google_business_url) ?? '';
        }

        if ($placeId === '') {
            return $empty;
        }

        $apiKey = config('services.google.places_api_key');
        if (!$apiKey) {
            return $empty;
        }

        $cacheKey = 'kdr_google_reviews_' . md5($placeId);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($setting, $placeId, $apiKey, $profileUrl, $writeUrl, $configured) {
            try {
                $response = Http::timeout(12)->get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $placeId,
                    'fields' => 'reviews,rating,user_ratings_total,name,url',
                    'key' => $apiKey,
                ]);

                $payload = $response->json();
                if (($payload['status'] ?? '') !== 'OK') {
                    return [
                        'configured' => $configured,
                        'profile_url' => $profileUrl ?: ($payload['result']['url'] ?? null),
                        'write_review_url' => $writeUrl,
                        'rating' => $setting->google_rating ? (float) $setting->google_rating : null,
                        'total' => $setting->google_review_count ? (int) $setting->google_review_count : null,
                        'reviews' => [],
                        'error' => $payload['error_message'] ?? 'Unable to load Google reviews.',
                    ];
                }

                $result = $payload['result'] ?? [];
                $rating = isset($result['rating']) ? (float) $result['rating'] : null;
                $total = isset($result['user_ratings_total']) ? (int) $result['user_ratings_total'] : null;

                if ($rating !== null || $total !== null) {
                    $setting->google_rating = $rating;
                    $setting->google_review_count = $total;
                    $setting->saveQuietly();
                }

                $reviews = collect($result['reviews'] ?? [])
                    ->map(fn (array $r) => [
                        'author_name' => (string) ($r['author_name'] ?? 'Google user'),
                        'author_photo' => $r['profile_photo_url'] ?? null,
                        'rating' => (int) ($r['rating'] ?? 5),
                        'text' => (string) ($r['text'] ?? ''),
                        'relative_time' => (string) ($r['relative_time_description'] ?? ''),
                        'time' => (int) ($r['time'] ?? 0),
                    ])
                    ->filter(fn (array $r) => $r['text'] !== '')
                    ->sortByDesc('time')
                    ->values()
                    ->all();

                return [
                    'configured' => true,
                    'profile_url' => $profileUrl ?: ($result['url'] ?? null),
                    'write_review_url' => $writeUrl,
                    'rating' => $rating,
                    'total' => $total,
                    'reviews' => $reviews,
                    'error' => null,
                ];
            } catch (\Throwable $e) {
                report($e);

                return [
                    'configured' => $configured,
                    'profile_url' => $profileUrl,
                    'write_review_url' => $writeUrl,
                    'rating' => $setting->google_rating ? (float) $setting->google_rating : null,
                    'total' => $setting->google_review_count ? (int) $setting->google_review_count : null,
                    'reviews' => [],
                    'error' => 'Could not connect to Google at the moment.',
                ];
            }
        });
    }

    /**
     * @return list<array{author_name: string, author_photo: ?string, rating: int, text: string, relative_time: string}>
     */
    public function getReviews(?Setting $setting = null, int $limit = 6): array
    {
        $data = $this->getData($setting);

        return array_slice($data['reviews'], 0, max(1, min($limit, 12)));
    }
}
