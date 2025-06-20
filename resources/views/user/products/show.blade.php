@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <div class="grid md:grid-cols-2 gap-8">
        {{-- Product Image --}}
        <div>
            @if(count($productImages) > 1)
                <!-- Image Slider -->
                <div class="swiper product-image-slider">
                    <div class="swiper-wrapper">
                        @foreach($productImages as $image)
                            <div class="swiper-slide">
                                <div class="aspect-w-1 aspect-h-1 w-full">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover rounded shadow" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            @else
                <!-- Single image display -->
                <div class="aspect-w-1 aspect-h-1 w-full">
                    <img src="{{ asset('storage/' . ($productImages[0] ?? $product->image)) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover rounded shadow" />
                </div>
            @endif
        </div>

        {{-- Product Details --}}
        <div class="space-y-5">
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500">Kategori: <span class="font-semibold">{{ $product->category->name }}</span></p>
            
            @if($product->has_variants && $minPrice != $maxPrice)
                <p class="text-green-600 text-2xl font-bold">Rp {{ number_format($minPrice) }} - Rp {{ number_format($maxPrice) }}</p>
            @else
                <p class="text-green-600 text-2xl font-bold">Rp {{ number_format($product->price ?? $minPrice) }}</p>
            @endif
            
            <p class="text-gray-700">{{ $product->description }}</p>

            {{-- Variant Selection --}}
            @if($product->has_variants && count($availableVariants) > 0)
                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-1">Pilih Varian</p>
                    <input type="hidden" name="variant_id" id="selectedVariantId" required>
                    
                    {{-- Variant 1 Selection --}}
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Pilih Motif:</p>
                        <div class="flex flex-wrap gap-2" id="variant1Container">
                            @php
                                $variant1Options = collect($availableVariants)->groupBy('variant_1');
                            @endphp
                            @foreach($variant1Options as $variant1Name => $variants)
                                <button type="button"
                                    data-variant1="{{ $variant1Name }}"
                                    class="variant1-option px-4 py-2 rounded-lg text-sm font-medium border-2 transition-all duration-200 bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:bg-blue-50">
                                    {{ $variant1Name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Variant 2 Selection --}}
                    <div class="mb-4 hidden" id="variant2Container">
                        <p class="text-sm font-medium text-gray-600 mb-2">Pilih Ukuran:</p>
                        <div class="flex flex-wrap gap-2" id="variant2Options">
                            {{-- Populated by JavaScript --}}
                        </div>
                    </div>

                    <p id="variantError" class="text-red-500 text-sm hidden mt-1">Pilih varian terlebih dahulu</p>
                </div>

                <script type="application/json" id="variantsData">
                    @json($availableVariants)
                </script>
            @else
                <p class="text-sm text-gray-600">Stok: <strong>{{ $total_stock }}</strong></p>
            @endif

            {{-- Add to Cart Form --}}
            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4" id="cartForm">
                    @csrf
                    <input type="hidden" name="variant_id" id="formVariantId" value="{{ $product->has_variants ? '' : 'none' }}">

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-300 rounded">
                            <button type="button" class="px-3 py-1 decrement-btn">-</button>
                            <input type="number" name="qty" value="1" min="1" 
                                   max="{{ $product->has_variants ? '' : $total_stock }}" 
                                   class="w-12 text-center border-x border-gray-300 py-1"
                                   id="quantityInput">
                            <button type="button" class="px-3 py-1 increment-btn">+</button>
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded" id="addToCartBtn">
                            Masukkan ke Keranjang
                        </button>
                    </div>

                    @if(session('error'))
                        <div class="text-red-500">{{ session('error') }}</div>
                    @endif
                    <div id="stockError" class="text-red-500 text-sm hidden">
                        Stok tidak mencukupi. Stok tersedia: <span id="availableStock"></span>
                    </div>
                </form>
            @else
                <button onclick="openLoginModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Masukkan ke Keranjang
                </button>
            @endauth
        </div>
    </div>

    {{-- Login Modal --}}
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 max-w-sm text-center">
            <h2 class="text-xl font-bold mb-4">Silakan Login atau Register</h2>
            <p class="mb-4">Untuk menambahkan produk ke keranjang, kamu harus login dulu.</p>
            <a href="{{ route('login') }}" class="block mb-2 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Login</a>
            <a href="{{ route('register') }}" class="block bg-gray-600 hover:bg-gray-700 text-white py-2 rounded">Register</a>
            <button onclick="closeLoginModal()" class="mt-4 text-gray-600 underline">Tutup</button>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Produk Terkait</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                    <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <a href="{{ route('products.show', $related->id) }}">
                            @php
                                $relatedImages = is_string($related->images) ? json_decode($related->images, true) : $related->images;
                                $firstImage = is_array($relatedImages) && !empty($relatedImages) ? $relatedImages[0] : $related->image;
                            @endphp
                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $related->name }}" class="w-full h-40 object-cover">
                            <div class="p-3">
                                <h3 class="font-semibold text-sm line-clamp-2">{{ $related->name }}</h3>
                                <p class="text-green-600 font-bold mt-1">Rp {{ number_format($related->price) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Review Section --}}
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4">Ulasan Produk</h2>

        {{-- Average Rating --}}
        @if($product->totalReviews() > 0)
            <div class="flex items-center mb-4">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->averageRating()))
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @elseif($i == ceil($product->averageRating()) && $product->averageRating() - floor($product->averageRating()) > 0)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 1l2.5 6.5H19l-5 4.5 1.5 6.5-6-4.5-6 4.5 1.5-6.5-5-4.5h6.5L10 1z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                    <span class="ml-2 text-gray-600">{{ number_format($product->averageRating(), 1) }} ({{ $product->totalReviews() }} ulasan)</span>
                </div>
            </div>
        @else
            <p class="text-gray-600">Belum ada ulasan untuk produk ini.</p>
        @endif

        {{-- Review List --}}
        @if($product->reviews->count() > 0)
            <div class="space-y-4 mb-6">
                @foreach($product->reviews->take(3) as $review)
                    <div class="border-b pb-4">
                        <div class="flex items-center mb-2">
                            <div class="font-semibold">{{ $review->user->name }}</div>
                            <div class="flex ml-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-700">{{ $review->comment }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $review->created_at->format('d M Y') }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- See all reviews link --}}
        @if($product->reviews->count() > 3)
            <a href="{{ route('products.reviews.index', $product) }}" class="text-blue-600 hover:underline">
                Lihat semua ulasan ({{ $product->reviews->count() }})
            </a>
        @endif

        {{-- Review Form --}}
        @auth
            @if($canReview)
                <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Beri Ulasan</h3>
                    <form action="{{ route('products.review.store', $product) }}" method="POST">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $transactionId }}">

                        <div class="mb-4">
                            <label for="rating" class="block text-gray-700 mb-2">Rating</label>
                            <select name="rating" id="rating" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rating') border-red-500 @enderror">
                                <option value="" disabled selected>Pilih rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ (old('rating') == $i || (isset($rating) && $rating == $i)) ? 'selected' : '' }}>
                                        {{ $i }} {{ Str::plural('Bintang', $i) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="block text-gray-700 mb-2">Ulasan</label>
                            <textarea name="comment" id="comment" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comment') }}</textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    // Initialize image slider if multiple images exist
    @if(count($productImages) > 1)
        const swiper = new Swiper('.product-image-slider', {
            loop: true,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    @endif

    // Variant selection logic
    const variant1Buttons = document.querySelectorAll('.variant1-option');
    const variant2Container = document.getElementById('variant2Container');
    const variant2Options = document.getElementById('variant2Options');
    const selectedVariantIdInput = document.getElementById('formVariantId');
    const variantError = document.getElementById('variantError');
    const stockError = document.getElementById('stockError');
    const quantityInput = document.getElementById('quantityInput');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const variantsData = JSON.parse(document.getElementById('variantsData').textContent);

    let selectedVariant = null;

    // Variant 1 selection
    variant1Buttons.forEach(button => {
        button.addEventListener('click', () => {
            variant1Buttons.forEach(b => {
                b.classList.remove('border-blue-500', 'bg-blue-100', 'text-blue-700');
                b.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
            });
            
            button.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
            button.classList.add('border-blue-500', 'bg-blue-100', 'text-blue-700');
            
            const selectedVariant1 = button.dataset.variant1;
            showVariant2Options(selectedVariant1);
            
            selectedVariant = null;
            selectedVariantIdInput.value = '';
            variantError.classList.add('hidden');
            stockError.classList.add('hidden');
            addToCartBtn.disabled = true;
            quantityInput.value = 1;
        });
    });

    function showVariant2Options(variant1Name) {
        const filteredVariants = variantsData.filter(variant => variant.variant_1 === variant1Name && variant.stock > 0);
        
        variant2Options.innerHTML = '';
        
        if (filteredVariants.length === 0) {
            variant2Options.innerHTML = '<p class="text-sm text-gray-500">Tidak ada varian yang tersedia</p>';
            addToCartBtn.disabled = true;
            return;
        }

        filteredVariants.forEach(variant => {
            const button = document.createElement('button');
            button.type = 'button';
            button.dataset.variantId = variant.id;
            button.dataset.stock = variant.stock;
            button.dataset.variant2 = variant.variant_2;
            
            button.className = 'variant2-option px-4 py-3 rounded-lg text-sm font-medium border-2 transition-all duration-200 bg-white text-gray-700 border-gray-300 hover:border-green-400 hover:bg-green-50';
            
            button.innerHTML = `
                <div class="text-center">
                    <div class="font-semibold text-base">
                        ${variant.variant_2}
                    </div>
                    <div class="text-xs text-gray-500 mt-1 border-t border-gray-200 pt-1">
                        Stok: ${variant.stock}
                    </div>
                </div>
            `;
            
            button.addEventListener('click', () => {
                document.querySelectorAll('.variant2-option').forEach(b => {
                    b.classList.remove('border-green-500', 'bg-green-100', 'text-green-700');
                    b.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
                });
                
                button.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                button.classList.add('border-green-500', 'bg-green-100', 'text-green-700');
                
                selectedVariant = variant;
                selectedVariantIdInput.value = variant.id;
                
                // Update quantity input
                quantityInput.max = variant.stock;
                quantityInput.value = 1;
                document.getElementById('availableStock').textContent = variant.stock;
                
                variantError.classList.add('hidden');
                stockError.classList.add('hidden');
                addToCartBtn.disabled = false;
            });
            
            variant2Options.appendChild(button);
        });
        
        variant2Container.classList.remove('hidden');
    }

    // Quantity input handling
    document.querySelectorAll('.increment-btn').forEach(button => {
        button.addEventListener('click', () => {
            const max = selectedVariant ? selectedVariant.stock : parseInt('{{ $total_stock }}');
            const val = parseInt(quantityInput.value) || 1;
            if (val < max) {
                quantityInput.value = val + 1;
                stockError.classList.add('hidden');
            } else {
                stockError.innerHTML = `Stok tidak mencukupi. Stok tersedia: ${max}`;
                stockError.classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(button => {
        button.addEventListener('click', () => {
            const val = parseInt(quantityInput.value) || 1;
            if (val > 1) {
                quantityInput.value = val - 1;
                stockError.classList.add('hidden');
            }
        });
    });

    // Real-time quantity validation
    quantityInput.addEventListener('change', () => {
        const max = selectedVariant ? selectedVariant.stock : parseInt('{{ $total_stock }}');
        const val = parseInt(quantityInput.value) || 1;
        
        if (val > max) {
            quantityInput.value = max;
            stockError.innerHTML = `Stok tidak mencukupi. Stok tersedia: ${max}`;
            stockError.classList.remove('hidden');
        } else if (val < 1) {
            quantityInput.value = 1;
        } else {
            stockError.classList.add('hidden');
        }
    });

    // Form submission validation
    const cartForm = document.getElementById('cartForm');
    cartForm.addEventListener('submit', e => {
        if (variantsData.length > 0 && !selectedVariantIdInput.value) {
            e.preventDefault();
            variantError.classList.remove('hidden');
            window.scrollTo({
                top: variantError.offsetTop - 100,
                behavior: 'smooth'
            });
            return;
        }
        
        let maxStock;
        if (selectedVariant) {
            maxStock = selectedVariant.stock;
        } else {
            maxStock = parseInt('{{ $total_stock }}') || 0;
        }
        
        const requestedQty = parseInt(quantityInput.value) || 0;
        
        if (requestedQty > maxStock) {
            e.preventDefault();
            stockError.innerHTML = `Stok tidak mencukupi. Stok tersedia: ${maxStock}`;
            stockError.classList.remove('hidden');
            window.scrollTo({
                top: stockError.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    });

    // Login modal functions
    function openLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
    }
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<style>
    .swiper {
        width: 100%;
        aspect-ratio: 1/1;
    }
    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .aspect-w-1 {
        position: relative;
        width: 100%;
    }
    .aspect-w-1::before {
        content: '';
        display: block;
        padding-bottom: 100%;
    }
    .aspect-h-1 {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    
    #stockError {
        margin-top: 0.5rem;
    }
    
    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .variant-option {
        min-width: 90px;
        min-height: 75px;
    }
    
    .variant-option:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection