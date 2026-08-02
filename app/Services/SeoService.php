<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class SeoService
{
    /**
     * Generate meta tags for a page
     */
    public function generateMetaTags(array $data): array
    {
        return [
            'title' => $this->generateTitle($data['title'] ?? '', $data['site_name'] ?? ''),
            'description' => $data['description'] ?? '',
            'keywords' => $data['keywords'] ?? '',
            'author' => $data['author'] ?? config('app.name'),
            'robots' => $data['robots'] ?? 'index, follow',
            'canonical' => $data['canonical'] ?? url()->current(),
            'og_type' => $data['og_type'] ?? 'website',
            'og_title' => $data['og_title'] ?? $data['title'] ?? '',
            'og_description' => $data['og_description'] ?? $data['description'] ?? '',
            'og_image' => $data['og_image'] ?? asset('images/og-default.jpg'),
            'og_url' => $data['og_url'] ?? url()->current(),
            'twitter_card' => $data['twitter_card'] ?? 'summary_large_image',
            'twitter_title' => $data['twitter_title'] ?? $data['title'] ?? '',
            'twitter_description' => $data['twitter_description'] ?? $data['description'] ?? '',
            'twitter_image' => $data['twitter_image'] ?? $data['og_image'] ?? '',
        ];
    }

    /**
     * Generate SEO title with site name
     */
    protected function generateTitle(string $title, string $siteName): string
    {
        if (empty($title)) {
            return $siteName;
        }

        return "{$title} | {$siteName}";
    }

    /**
     * Generate JSON-LD structured data for product
     */
    public function generateProductSchema(Product $product): array
    {
        $schema = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->short_description ?? $product->description),
            'image' => $product->images->isNotEmpty() 
                ? collect($product->images)->pluck('image_url')->toArray()
                : asset('images/product-placeholder.jpg'),
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? config('app.name'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => config('app.currency', 'USD'),
                'price' => $product->price,
                'availability' => $product->stock_quantity > 0 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                ],
            ],
        ];

        // Add aggregate rating if reviews exist
        if ($product->reviews()->count() > 0) {
            $averageRating = $product->reviews()->avg('rating');
            $reviewCount = $product->reviews()->count();

            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($averageRating, 1),
                'reviewCount' => $reviewCount,
            ];
        }

        // Add offers for variations
        if ($product->variations()->count() > 0) {
            $offers = [];
            foreach ($product->variations as $variation) {
                $offers[] = [
                    '@type' => 'Offer',
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'priceCurrency' => config('app.currency', 'USD'),
                    'availability' => $variation->stock_quantity > 0
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                ];
            }
            $schema['offers'] = $offers;
        }

        return $schema;
    }

    /**
     * Generate JSON-LD structured data for breadcrumb
     */
    public function generateBreadcrumbSchema(array $items): array
    {
        $itemListElements = [];

        foreach ($items as $index => $item) {
            $itemListElements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElements,
        ];
    }

    /**
     * Generate JSON-LD for organization
     */
    public function generateOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('images/logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => config('settings.phone', ''),
                'contactType' => 'customer service',
            ],
        ];
    }

    /**
     * Generate JSON-LD for website
     */
    public function generateWebsiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url') . '/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Generate sitemap XML content
     */
    public function generateSitemap(): string
    {
        $urls = [];

        // Homepage
        $urls[] = $this->buildSitemapUrl(config('app.url'), now(), 'daily', 1.0);

        // Products
        Product::where('is_active', true)->each(function ($product) use (&$urls) {
            $urls[] = $this->buildSitemapUrl(
                route('product.show', $product->slug),
                $product->updated_at,
                'weekly',
                0.8
            );
        });

        // Categories
        Category::where('is_active', true)->each(function ($category) use (&$urls) {
            $urls[] = $this->buildSitemapUrl(
                route('category.show', $category->slug),
                $category->updated_at,
                'weekly',
                0.7
            );
        });

        // Brands
        Brand::where('is_active', true)->each(function ($brand) use (&$urls) {
            $urls[] = $this->buildSitemapUrl(
                route('brand.show', $brand->slug),
                $brand->updated_at,
                'weekly',
                0.6
            );
        });

        // CMS Pages
        $cmsPages = \App\Models\CmsPage::where('is_active', true)->get();
        foreach ($cmsPages as $page) {
            $urls[] = $this->buildSitemapUrl(
                route('page.show', $page->slug),
                $page->updated_at,
                'monthly',
                0.5
            );
        }

        // Blog posts
        $blogPosts = \App\Models\BlogPost::where('is_active', true)->get();
        foreach ($blogPosts as $post) {
            $urls[] = $this->buildSitemapUrl(
                route('blog.show', $post->slug),
                $post->updated_at,
                'weekly',
                0.6
            );
        }

        return $this->buildSitemapXml($urls);
    }

    /**
     * Build sitemap URL entry
     */
    protected function buildSitemapUrl(string $loc, \DateTime $lastmod, string $changefreq, float $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod->format('Y-m-d'),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /**
     * Build sitemap XML
     */
    protected function buildSitemapXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate robots.txt content
     */
    public function generateRobotsTxt(): string
    {
        $content = "User-agent: *\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /cart\n";
        $content .= "Disallow: /checkout\n";
        $content .= "Disallow: /account/\n";
        $content .= "Disallow: /wishlist\n";
        $content .= "Disallow: /compare\n";
        $content .= "Disallow: /*?sort=\n";
        $content .= "Disallow: /*?filter=\n";
        $content .= "\n";
        $content .= "Sitemap: " . config('app.url') . "/sitemap.xml\n";

        return $content;
    }

    /**
     * Cache SEO data for performance
     */
    public function getCachedSeoData(string $key, callable $callback, int $ttl = 3600): mixed
    {
        return Cache::remember("seo_{$key}", $ttl, $callback);
    }

    /**
     * Clear SEO cache
     */
    public function clearSeoCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget("seo_{$key}");
        } else {
            Cache::tags(['seo'])->flush();
        }
    }

    /**
     * Generate canonical URL for paginated pages
     */
    public function getCanonicalUrl(string $baseUrl, ?int $page = null): string
    {
        if ($page && $page > 1) {
            return "{$baseUrl}?page={$page}";
        }

        return $baseUrl;
    }

    /**
     * Check if page should be indexed
     */
    public function shouldBeIndexed(array $data): bool
    {
        if (isset($data['no_index']) && $data['no_index']) {
            return false;
        }

        if (isset($data['robots']) && in_array($data['robots'], ['noindex', 'noindex, nofollow'])) {
            return false;
        }

        return true;
    }
}
