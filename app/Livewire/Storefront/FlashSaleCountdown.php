<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Models\FlashSale;
use Carbon\Carbon;

class FlashSaleCountdown extends Component
{
    public ?FlashSale $flashSale = null;
    public string $endTime = '';
    public bool $isActive = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(?int $flashSaleId = null): void
    {
        if ($flashSaleId) {
            $this->flashSale = FlashSale::with('products')->find($flashSaleId);
        } else {
            $this->flashSale = FlashSale::where('is_active', true)
                ->where('starts_at', '<=', Carbon::now())
                ->where('ends_at', '>', Carbon::now())
                ->orderBy('starts_at', 'desc')
                ->first();
        }

        if ($this->flashSale) {
            $this->endTime = $this->flashSale->ends_at->toISOString();
            $this->isActive = true;
        }
    }

    public function render()
    {
        return view('livewire.storefront.flash-sale-countdown');
    }
}
