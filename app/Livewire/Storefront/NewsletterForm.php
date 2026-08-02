<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Validator;

class NewsletterForm extends Component
{
    public string $email = '';
    public bool $subscribed = false;
    public ?string $message = null;

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ];

    public function mount(): void
    {
        // Check if already subscribed in this session
        if (session()->has('newsletter_subscribed')) {
            $this->subscribed = true;
            $this->message = session('newsletter_message');
        }
    }

    public function subscribe(): void
    {
        $this->validate();

        try {
            NewsletterSubscriber::create([
                'email' => $this->email,
                'is_active' => true,
                'subscribed_at' => now(),
            ]);

            $this->subscribed = true;
            $this->message = 'Thank you for subscribing to our newsletter!';
            $this->email = '';
            
            session()->flash('newsletter_subscribed', true);
            session()->flash('newsletter_message', $this->message);
            
            $this->dispatch('showSuccess', message: $this->message);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->subscribed = true;
                $this->message = 'You are already subscribed!';
                $this->dispatch('showSuccess', message: $this->message);
            } else {
                $this->dispatch('showError', message: 'Failed to subscribe. Please try again.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Failed to subscribe. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.storefront.newsletter-form');
    }
}
