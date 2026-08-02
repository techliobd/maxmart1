<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@example.com',
                'rating' => 5,
                'title' => 'Amazing shopping experience!',
                'content' => 'MaxMart has become my go-to online store. The product quality is excellent, shipping is fast, and customer service is always helpful. Highly recommended!',
                'avatar' => 'https://placehold.co/100x100/f5f5f5/333333?text=SJ',
                'is_approved' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'm.chen@example.com',
                'rating' => 5,
                'title' => 'Best prices I\'ve found',
                'content' => 'I compared prices across multiple sites and MaxMart consistently offers the best deals. Plus, the free shipping threshold is very reasonable.',
                'avatar' => 'https://placehold.co/100x100/f5f5f5/333333?text=MC',
                'is_approved' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.r@example.com',
                'rating' => 4,
                'title' => 'Great selection of products',
                'content' => 'Love the variety of brands and categories available. Found everything I needed for my home office setup in one place.',
                'avatar' => 'https://placehold.co/100x100/f5f5f5/333333?text=ER',
                'is_approved' => true,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.t@example.com',
                'rating' => 5,
                'title' => 'Excellent customer service',
                'content' => 'Had an issue with my order and the support team resolved it within hours. They even upgraded my shipping for free as an apology. That\'s how you keep customers!',
                'avatar' => 'https://placehold.co/100x100/f5f5f5/333333?text=DT',
                'is_approved' => true,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Lisa Park',
                'email' => 'lisa.p@example.com',
                'rating' => 5,
                'title' => 'Fast delivery!',
                'content' => 'Ordered on Monday, received on Wednesday. The packaging was secure and everything arrived in perfect condition. Will definitely shop again.',
                'avatar' => 'https://placehold.co/100x100/f5f5f5/333333?text=LP',
                'is_approved' => true,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'James Wilson',
                'email' => 'j.wilson@example.com',
                'rating' => 4,
                'title' => 'Quality products',
                'content' => 'The Nike shoes I bought are authentic and exactly as described. Appreciate the detailed product photos and specifications.',
                'avatar' => 'https://placehold.co/100x100/f5f5f5/333333?text=JW',
                'is_approved' => true,
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($testimonials as $testimonialData) {
            Testimonial::updateOrCreate(
                ['email' => $testimonialData['email']],
                $testimonialData
            );
        }
    }
}
