@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Banner Slider -->
    <div class="relative rounded-xl overflow-hidden mb-8 md:mb-12 shadow-lg">
        <div class="swiper bannerSwiper h-[200px] md:h-[400px] lg:h-[500px] w-full">
            <div class="swiper-wrapper">
                <!-- Slide 1 - Fashion -->
                <div class="swiper-slide relative">
                    <img src="https://source.unsplash.com/random/800x400/?fashion,sale" 
                         class="w-full h-full object-cover" 
                         alt="Fashion Sale"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Summer Fashion</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Up to 50% off selected items</p>
                            <a href="#" class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                Shop Collection
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Electronics -->
                <div class="swiper-slide relative">
                    <img src="https://source.unsplash.com/random/800x400/?electronics,gadgets" 
                         class="w-full h-full object-cover" 
                         alt="Electronics"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">New Gadgets</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Latest tech at best prices</p>
                            <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                View Deals
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Home -->
                <div class="swiper-slide relative">
                    <img src="https://source.unsplash.com/random/800x400/?home,decor" 
                         class="w-full h-full object-cover" 
                         alt="Home Decor"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Home Essentials</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Refresh your living space</p>
                            <a href="#" class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                Discover Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination & Navigation -->
            <div class="swiper-pagination !bottom-2 md:!bottom-4"></div>
            <div class="swiper-button-next !hidden md:!flex !text-white !w-8 !h-8 md:!w-10 md:!h-10"></div>
            <div class="swiper-button-prev !hidden md:!flex !text-white !w-8 !h-8 md:!w-10 md:!h-10"></div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="text-center mb-8 md:mb-12">
        <h3 class="text-xl md:text-2xl font-bold mb-2 md:mb-4 text-gray-800">Welcome to MiniStore!</h3>
        <p class="text-gray-600 max-w-xl mx-auto text-sm md:text-base">
            Discover amazing deals on fashion, electronics, and home essentials all in one place.
        </p>
    </div>

    {{-- Latest Products --}}
    <section class="mb-10">
        <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">New Arrivals</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
            @foreach ($latestProducts as $product)
                <a href="{{ route('products.show', $product->id) }}" class="border rounded-lg p-3 hover:shadow-md group transition duration-300">
                    <div class="aspect-square overflow-hidden mb-2 rounded-lg">
                        <img src="{{ asset('storage/' . $product->photo) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                             loading="lazy">
                    </div>
                    <h3 class="font-semibold text-sm md:text-base line-clamp-1">{{ $product->name }}</h3>
                    <p class="text-green-600 font-bold text-sm md:text-base">Rp {{ number_format($product->price) }}</p>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
<style>
.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-button-next, 
.swiper-button-prev {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(2px);
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    transition: all 0.3s ease;
    display: none;
}

@media (min-width: 768px) {
    .swiper-button-next, 
    .swiper-button-prev {
        display: flex;
    }
}

.swiper-button-next::after, 
.swiper-button-prev::after {
    font-size: 1rem;
    font-weight: bold;
}

.swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    background: white;
    opacity: 0.7;
}

.swiper-pagination-bullet-active {
    opacity: 1;
    background: #3b82f6;
}

/* Product card hover effect */
.group:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Line clamp for product names */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Aspect ratio for product images */
.aspect-square {
    aspect-ratio: 1/1;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Banner Swiper
    const bannerSwiper = new Swiper(".bannerSwiper", {
        loop: true,
        autoplay: {
            delay: 5000,
            pauseOnMouseEnter: true,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 800,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    // Pause on hover
    const bannerContainer = document.querySelector('.bannerSwiper');
    if (bannerContainer) {
        bannerContainer.addEventListener('mouseenter', () => {
            bannerSwiper.autoplay.stop();
        });
        bannerContainer.addEventListener('mouseleave', () => {
            bannerSwiper.autoplay.start();
        });
    }
});
</script>
@endsection