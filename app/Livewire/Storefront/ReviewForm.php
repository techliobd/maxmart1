<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewForm extends Component
{
    public Product $product;
    public int $rating = 5;
    public string $title = '';
    public string $comment = '';
    public bool $isVerifiedPurchase = false;
    public array $images = [];
    public bool $showForm = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'required|string|max:200',
        'comment' => 'required|string|min:10|max:2000',
        'images.*' => 'image|max:2048',
    ];

    public function mount(Product $product): void
    {
        $this->product = $product;
        
        // Check if user has purchased this product
        if (Auth::check()) {
            $this->isVerifiedPurchase = \App\Models\OrderItem::whereHas('order', function ($query) {
                $query->where('customer_id', Auth::id())->where('status', 'completed');
            })->where('product_id', $product->id)->exists();
        }
    }

    public function toggleForm(): void
    {
        if (!Auth::check()) {
            $this->dispatch('showError', message: 'Please login to write a review');
            return;
        }
        
        $this->showForm = !$this->showForm;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function submit(): void
    {
        if (!Auth::check()) {
            $this->dispatch('showError', message: 'Please login to write a review');
            return;
        }

        $this->validate();

        try {
            $review = new Review();
            $review->product_id = $this->product->id;
            $review->customer_id = Auth::id();
            $review->rating = $this->rating;
            $review->title = $this->title;
            $review->comment = $this->comment;
            $review->is_verified_purchase = $this->isVerifiedPurchase;
            $review->status = 'pending'; // Requires admin approval
            $review->save();

            // Handle image uploads if any
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    $path = $image->store('reviews', 'public');
                    $review->images()->create(['image_path' => $path]);
                }
            }

            $this->reset(['title', 'comment', 'rating', 'images', 'showForm']);
            $this->rating = 5;
            
            $this->dispatch('showSuccess', message: 'Review submitted successfully! It will appear after approval.');
            $this->dispatch('reviewSubmitted');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Failed to submit review. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.storefront.review-form');
    }
}
