<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = CmsPage::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }

    public function home()
    {
        return redirect()->route('home');
    }

    public function about()
    {
        $page = CmsPage::where('slug', 'about-us')->where('is_published', true)->first();
        
        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }

    public function contact()
    {
        return view('storefront.pages.contact');
    }

    public function faq()
    {
        $page = CmsPage::where('slug', 'faq')->where('is_published', true)->first();
        
        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }

    public function privacy()
    {
        $page = CmsPage::where('slug', 'privacy-policy')->where('is_published', true)->first();
        
        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }

    public function terms()
    {
        $page = CmsPage::where('slug', 'terms-conditions')->where('is_published', true)->first();
        
        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }

    public function shipping()
    {
        $page = CmsPage::where('slug', 'shipping-info')->where('is_published', true)->first();
        
        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }

    public function returns()
    {
        $page = CmsPage::where('slug', 'returns-refunds')->where('is_published', true)->first();
        
        if (!$page) {
            abort(404);
        }

        return view('storefront.pages.show', compact('page'));
    }
}
