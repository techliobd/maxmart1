<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login to write a review.');
        }

        $product = Product::findOrFail($request->product_id);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('customer_id', Auth::user()->customer?->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Create review
        $review = Review::create([
            'product_id' => $product->id,
            'customer_id' => Auth::user()->customer?->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => false, // Require admin approval
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                
                ReviewImage::create([
                    'review_id' => $review->id,
                    'url' => $path,
                ]);
            }
        }

        return back()->with('success', 'Your review has been submitted and is awaiting approval.');
    }

    public function helpful(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id'
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $review = Review::findOrFail($request->review_id);
        
        // Check if user already voted
        $alreadyVoted = $review->votes()
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyVoted) {
            return response()->json(['error' => 'You have already voted on this review'], 422);
        }

        $review->votes()->create([
            'user_id' => Auth::id(),
            'is_helpful' => true
        ]);

        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }

    public function notHelpful(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id'
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $review = Review::findOrFail($request->review_id);
        
        // Check if user already voted
        $alreadyVoted = $review->votes()
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyVoted) {
            return response()->json(['error' => 'You have already voted on this review'], 422);
        }

        $review->votes()->create([
            'user_id' => Auth::id(),
            'is_helpful' => false
        ]);

        $review->increment('not_helpful_count');

        return response()->json([
            'success' => true,
            'not_helpful_count' => $review->not_helpful_count
        ]);
    }
}
