<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\FlashSale;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\HomepageSection;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['primaryImage', 'category'])
            ->limit(8)
            ->get();

        $newArrivals = Product::where('is_active', true)
            ->latest('id')
            ->with(['primaryImage', 'category'])
            ->limit(8)
            ->get();

        $onSaleProducts = Product::where('is_on_sale', true)
            ->where('is_active', true)
            ->with(['primaryImage', 'category'])
            ->limit(8)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->withCount('products')
            ->limit(6)
            ->get();

        $brands = Brand::where('is_featured', true)
            ->limit(8)
            ->get();

        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        $flashSales = FlashSale::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->with(['products' => fn($q) => $q->limit(10)])
            ->latest()
            ->first();

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        $homepageSections = HomepageSection::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $blogPosts = BlogPost::where('is_published', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('storefront.home', compact(
            'featuredProducts',
            'newArrivals',
            'onSaleProducts',
            'categories',
            'brands',
            'banners',
            'flashSales',
            'testimonials',
            'homepageSections',
            'blogPosts'
        ));
    }
}
