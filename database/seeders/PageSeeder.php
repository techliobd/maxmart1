<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About MaxMart</h1><p>Welcome to MaxMart, your premier destination for quality products at exceptional prices. Founded in 2024, we have quickly become a trusted name in e-commerce.</p><h2>Our Mission</h2><p>To provide customers with an unparalleled shopping experience, offering a wide selection of premium products backed by excellent customer service.</p><h2>Why Choose Us?</h2><ul><li>Wide product selection across multiple categories</li><li>Competitive pricing and regular discounts</li><li>Fast and reliable shipping</li><li>Secure payment options</li><li>24/7 customer support</li></ul><p>Thank you for choosing MaxMart. We look forward to serving you!</p>',
                'meta_title' => 'About MaxMart - Your Trusted Online Store',
                'meta_description' => 'Learn about MaxMart\'s mission to provide quality products and exceptional service.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<h1>Get in Touch</h1><p>We\'d love to hear from you! Reach out to us with any questions, concerns, or feedback.</p><h2>Contact Information</h2><ul><li><strong>Email:</strong> support@maxmart.com</li><li><strong>Phone:</strong> +1 (555) 123-4567</li><li><strong>Address:</strong> 123 Commerce Street, New York, NY 10001</li></ul><h2>Business Hours</h2><p>Monday - Friday: 9:00 AM - 6:00 PM EST<br>Saturday: 10:00 AM - 4:00 PM EST<br>Sunday: Closed</p><h2>Customer Support</h2><p>For immediate assistance, please use our live chat feature available on every page.</p>',
                'meta_title' => 'Contact MaxMart - Get in Touch',
                'meta_description' => 'Contact MaxMart customer support via email, phone, or visit our store.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h1>Terms of Service</h1><p>Last updated: January 1, 2024</p><h2>1. Acceptance of Terms</h2><p>By accessing and using MaxMart, you accept and agree to be bound by these terms of service.</p><h2>2. Use License</h2><p>Permission is granted to temporarily browse the materials on MaxMart for personal, non-commercial transitory viewing only.</p><h2>3. Product Information</h2><p>We strive to provide accurate product descriptions and images, but we do not warrant that product descriptions are complete, current, or error-free.</p><h2>4. Pricing</h2><p>All prices are subject to change without notice. We reserve the right to modify or discontinue products at any time.</p><h2>5. Order Acceptance</h2><p>Your order constitutes an offer to buy. All orders are subject to acceptance and availability.</p><h2>6. Limitation of Liability</h2><p>MaxMart shall not be liable for any damages arising from the use or inability to use our website.</p>',
                'meta_title' => 'Terms of Service - MaxMart',
                'meta_description' => 'Read MaxMart\'s terms of service and conditions of use.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>Last updated: January 1, 2024</p><h2>Information We Collect</h2><p>We collect information you provide directly to us, including name, email address, shipping address, and payment information.</p><h2>How We Use Your Information</h2><ul><li>To process and fulfill your orders</li><li>To communicate with you about products and promotions</li><li>To improve our services</li><li>To prevent fraud</li></ul><h2>Information Sharing</h2><p>We do not sell your personal information. We may share your information with service providers who assist in our operations.</p><h2>Data Security</h2><p>We implement appropriate technical and organizational measures to protect your personal information.</p><h2>Your Rights</h2><p>You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.</p><h2>Cookies</h2><p>We use cookies to enhance your browsing experience and analyze site traffic.</p>',
                'meta_title' => 'Privacy Policy - MaxMart',
                'meta_description' => 'Learn how MaxMart collects, uses, and protects your personal information.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'content' => '<h1>Shipping Policy</h1><h2>Processing Time</h2><p>Orders are processed within 1-2 business days. Orders placed on weekends or holidays will be processed the next business day.</p><h2>Shipping Methods</h2><ul><li><strong>Standard Shipping:</strong> 5-7 business days ($5.99)</li><li><strong>Express Shipping:</strong> 2-3 business days ($12.99)</li><li><strong>Next Day Delivery:</strong> 1 business day ($24.99)</li></ul><h2>Free Shipping</h2><p>Free standard shipping on orders over $50 within the continental United States.</p><h2>International Shipping</h2><p>We ship to select international destinations. Rates and delivery times vary by location.</p><h2>Order Tracking</h2><p>Once your order ships, you will receive a tracking number via email.</p><h2>Delivery Issues</h2><p>If your package is lost or damaged, contact us within 7 days of the expected delivery date.</p>',
                'meta_title' => 'Shipping Policy - MaxMart',
                'meta_description' => 'Learn about MaxMart\'s shipping options, rates, and delivery times.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Return Policy',
                'slug' => 'return-policy',
                'content' => '<h1>Return & Refund Policy</h1><h2>Eligibility</h2><p>Items must be returned within 30 days of delivery in original condition with tags attached.</p><h2>Non-Returnable Items</h2><ul><li>Gift cards</li><li>Personalized items</li><li>Intimate apparel (for hygiene reasons)</li><li>Final sale items</li></ul><h2>How to Return</h2><ol><li>Log into your account and request a return</li><li>Print the prepaid return label</li><li>Pack the item securely</li><li>Drop off at any authorized shipping location</li></ol><h2>Refund Processing</h2><p>Refunds are processed within 5-7 business days after we receive your return. Original shipping charges are non-refundable.</p><h2>Exchanges</h2><p>We offer free exchanges for size or color changes. Contact customer service to arrange an exchange.</p>',
                'meta_title' => 'Return Policy - MaxMart',
                'meta_description' => 'Understand MaxMart\'s return and refund policy for hassle-free returns.',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'content' => '<h1>Frequently Asked Questions</h1><h2>Account & Orders</h2><h3>How do I create an account?</h3><p>Click "Sign In" at the top of the page and select "Create Account". Enter your details and confirm your email.</p><h3>Can I modify my order after placing it?</h3><p>Contact us immediately. We can modify orders within 1 hour of placement if they haven\'t been shipped.</p><h2>Payment</h2><h3>What payment methods do you accept?</h3><p>We accept Visa, MasterCard, American Express, PayPal, Apple Pay, and Google Pay.</p><h3>Is my payment information secure?</h3><p>Yes, we use SSL encryption and never store your full credit card information.</p><h2>Shipping & Delivery</h2><h3>Do you ship internationally?</h3><p>Yes, we ship to over 50 countries. Shipping costs and times vary by location.</p><h3>How do I track my order?</h3><p>Check your email for the tracking number, or log into your account to view order status.</p><h2>Returns</h2><h3>What if I receive a damaged item?</h3><p>Contact us within 7 days with photos of the damage. We\'ll send a replacement or issue a full refund.</p>',
                'meta_title' => 'FAQ - MaxMart',
                'meta_description' => 'Find answers to frequently asked questions about shopping at MaxMart.',
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($pages as $pageData) {
            CmsPage::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
