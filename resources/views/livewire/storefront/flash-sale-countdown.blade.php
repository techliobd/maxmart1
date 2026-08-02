@if($isActive && $flashSale)
    <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white py-4 px-6 rounded-lg shadow-lg mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-bold">{{ $flashSale->title }}</h2>
                    @if($flashSale->description)
                        <p class="text-red-100 mt-1">{{ Str::limit($flashSale->description, 80) }}</p>
                    @endif
                </div>

                <!-- Countdown Timer -->
                <div 
                    x-data="{
                        endTime: new Date('{{ $endTime }}').getTime(),
                        timeLeft: { days: 0, hours: 0, minutes: 0, seconds: 0 },
                        updateTimer() {
                            const now = new Date().getTime();
                            const distance = this.endTime - now;
                            
                            if (distance < 0) {
                                this.timeLeft = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                                return;
                            }
                            
                            this.timeLeft = {
                                days: Math.floor(distance / (1000 * 60 * 60 * 24)),
                                hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                                minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                                seconds: Math.floor((distance % (1000 * 60)) / 1000)
                            };
                        }
                    }"
                    x-init="updateTimer(); setInterval(() => updateTimer(), 1000)"
                    class="flex items-center gap-3"
                >
                    <div class="text-center">
                        <div 
                            x-text="String(timeLeft.days).padStart(2, '0')"
                            class="bg-white text-red-600 font-bold text-xl w-14 h-14 flex items-center justify-center rounded-lg shadow"
                        ></div>
                        <span class="text-xs text-red-100 mt-1 block">Days</span>
                    </div>
                    <span class="text-2xl font-bold">:</span>
                    <div class="text-center">
                        <div 
                            x-text="String(timeLeft.hours).padStart(2, '0')"
                            class="bg-white text-red-600 font-bold text-xl w-14 h-14 flex items-center justify-center rounded-lg shadow"
                        ></div>
                        <span class="text-xs text-red-100 mt-1 block">Hours</span>
                    </div>
                    <span class="text-2xl font-bold">:</span>
                    <div class="text-center">
                        <div 
                            x-text="String(timeLeft.minutes).padStart(2, '0')"
                            class="bg-white text-red-600 font-bold text-xl w-14 h-14 flex items-center justify-center rounded-lg shadow"
                        ></div>
                        <span class="text-xs text-red-100 mt-1 block">Mins</span>
                    </div>
                    <span class="text-2xl font-bold">:</span>
                    <div class="text-center">
                        <div 
                            x-text="String(timeLeft.seconds).padStart(2, '0')"
                            class="bg-white text-red-600 font-bold text-xl w-14 h-14 flex items-center justify-center rounded-lg shadow"
                        ></div>
                        <span class="text-xs text-red-100 mt-1 block">Secs</span>
                    </div>
                </div>

                <a 
                    href="{{ route('shop') }}"
                    class="bg-white text-red-600 hover:bg-red-50 font-semibold py-3 px-6 rounded-lg transition-colors whitespace-nowrap"
                >
                    Shop Now
                </a>
            </div>
        </div>
    </div>
@endif
