<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Services\SeoService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    public function index(Request $request)
    {
        $query = BlogPost::where('is_published', true)
            ->with(['category', 'author', 'featuredImage']);

        // Category filter
        if ($request->filled('category')) {
            $query->where('blog_category_id', function($q) use ($request) {
                $q->select('id')->from('blog_categories')->where('slug', $request->category);
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest('published_at')->paginate(12)->withQueryString();

        $categories = BlogCategory::withCount('posts')->get();
        $popularPosts = BlogPost::where('is_published', true)
            ->orderByDesc('views_count')
            ->limit(5)
            ->get();
        $tags = BlogTag::withCount('posts')->orderBy('name')->get();

        return view('storefront.blog.index', compact('posts', 'categories', 'popularPosts', 'tags'));
    }

    public function show(BlogPost $post)
    {
        if (!$post->is_published) {
            abort(404);
        }

        // Increment views
        $post->increment('views_count');

        $post->load(['category', 'author', 'tags', 'comments.customer']);

        // Related posts
        $relatedPosts = BlogPost::where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return view('storefront.blog.show', compact('post', 'relatedPosts'));
    }

    public function category(BlogCategory $category)
    {
        $posts = $category->posts()
            ->where('is_published', true)
            ->with(['author', 'featuredImage'])
            ->latest('published_at')
            ->paginate(12);

        return view('storefront.blog.category', compact('category', 'posts'));
    }

    public function tag(BlogTag $tag)
    {
        $posts = $tag->posts()
            ->where('is_published', true)
            ->with(['category', 'author', 'featuredImage'])
            ->latest('published_at')
            ->paginate(12);

        return view('storefront.blog.tag', compact('tag', 'posts'));
    }
}
