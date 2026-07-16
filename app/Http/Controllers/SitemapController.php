<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Car;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        $static = [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('showCars'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('services.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('faq'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('blogs'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('reviews.index'), 'changefreq' => 'weekly', 'priority' => '0.6'],
            ['loc' => route('listYourProperty'), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach ($static as $entry) {
            $urls[] = array_merge($entry, ['lastmod' => now()->toAtomString()]);
        }

        try {
            Car::query()
                ->where('status', 'available')
                ->forRent()
                ->whereNotNull('slug')
                ->orderByDesc('updated_at')
                ->get(['slug', 'updated_at'])
                ->each(function (Car $car) use (&$urls) {
                    $urls[] = [
                        'loc' => route('carDetails', $car->slug),
                        'lastmod' => optional($car->updated_at)->toAtomString() ?? now()->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                });
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            Service::query()
                ->active()
                ->whereNotNull('slug')
                ->ordered()
                ->get(['slug', 'updated_at'])
                ->each(function (Service $service) use (&$urls) {
                    $urls[] = [
                        'loc' => route('services.show', $service->slug),
                        'lastmod' => optional($service->updated_at)->toAtomString() ?? now()->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.7',
                    ];
                });
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            Blog::query()
                ->where('status', 'Published')
                ->whereNotNull('slug')
                ->orderByDesc('updated_at')
                ->get(['slug', 'updated_at', 'published_at'])
                ->each(function (Blog $blog) use (&$urls) {
                    $lastmod = $blog->updated_at ?? $blog->published_at;
                    $urls[] = [
                        'loc' => route('singleBlog', $blog->slug),
                        'lastmod' => optional($lastmod)->toAtomString() ?? now()->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                });
        } catch (\Throwable $e) {
            report($e);
        }

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /dashboard',
            'Disallow: /Users',
            'Disallow: /admin/',
            'Disallow: /my-profile',
            'Disallow: /logouts',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /password/',
            'Disallow: /email/',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
