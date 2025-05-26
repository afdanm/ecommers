@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <div class="grid md:grid-cols-2 gap-8">
        {{-- Product Image --}}
        <div>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full max-h-[500px] object-cover rounded shadow">
        </div>

        {{-- Product Details --}}
        <div class="space-y-5">
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500">Kategori: <span class="font-semibold">{{ $product->category->name }}</span></p>
            <p class="text-green-600 text-2xl font-bold">Rp {{ number_format($product->price) }}</p>
            <p class="text-gray-700">{{ $product->description }}</p>

            {{-- Size Selection --}}
            @if($availableSizes->count() > 0)
                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-1">Pilih Ukuran</p>
                    <input type="hidden" name="size_id" id="selectedSizeId" required>
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableSizes as $size)
                            <button type="button"
                                data-size-id="{{ $size['id'] }}"
                                class="size-option px-3 py-1 rounded-full text-sm font-medium border {{ $size['available'] ? 'bg-blue-100 text-blue-700 border-blue-300 hover:bg-blue-200' : 'bg-gray-200 text-gray-500 border-gray-300 cursor-not-allowed line-through' }}"
                                {{ !$size['available'] ? 'disabled' : '' }}>
                                {{ $size['name'] }}
                                <span class="text-xs ml-1 text-gray-500">({{ $size['stock'] }})</span>
                            </button>
                        @endforeach
                    </div>
                    <p id="sizeError" class="text-red-500 text-sm hidden mt-1">Pilih ukuran terlebih dahulu</p>
                </div>
            @else
                <p class="text-sm text-gray-600">Stok: <strong>{{ $total_stock }}</strong></p>
            @endif

            {{-- Add to Cart Form --}}
            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4" id="cartForm">
                    @csrf
                    <input type="hidden" name="size_id" id="formSizeId" required>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-300 rounded">
                            <button type="button" class="px-3 py-1 decrement-btn">-</button>
                            <input type="number" name="qty" value="1" min="1" max="{{ $total_stock }}" class="w-12 text-center border-x border-gray-300 py-1">
                            <button type="button" class="px-3 py-1 increment-btn">+</button>
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Masukkan ke Keranjang
                        </button>
                    </div>

                    @if(session('error'))
                        <div class="text-red-500">{{ session('error') }}</div>
                    @endif
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
                                <path d="..." />
                            </svg>
                        @elseif($i == ceil($product->averageRating()) && $product->averageRating() - floor($product->averageRating()) > 0)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="..." />
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="..." />
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
                                        <path d="..." />
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
<script>
    function setRating(rating) {
        document.getElementById('rating-input').value = rating;
        const stars = document.querySelectorAll('.rating-star svg');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    const sizeButtons = document.querySelectorAll('.size-option');
    const selectedSizeIdInput = document.getElementById('formSizeId');
    const sizeError = document.getElementById('sizeError');

    sizeButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (button.disabled) return;
            sizeButtons.forEach(b => b.classList.remove('border-blue-600', 'bg-blue-300'));
            button.classList.add('border-blue-600', 'bg-blue-300');
            selectedSizeIdInput.value = button.dataset.sizeId;
            sizeError.classList.add('hidden');
        });
    });

    const cartForm = document.getElementById('cartForm');
    cartForm.addEventListener('submit', e => {
        if (selectedSizeIdInput && !selectedSizeIdInput.value && sizeButtons.length > 0) {
            e.preventDefault();
            sizeError.classList.remove('hidden');
        }
    });

    document.querySelectorAll('.increment-btn').forEach(button => {
        button.addEventListener('click', () => {
            const input = button.previousElementSibling;
            let max = parseInt(input.max) || 100;
            let val = parseInt(input.value) || 1;
            if (val < max) input.value = val + 1;
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(button => {
        button.addEventListener('click', () => {
            const input = button.nextElementSibling;
            let val = parseInt(input.value) || 1;
            if (val > 1) input.value = val - 1;
        });
    });

    function openLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
    }
</script>
@endsection
