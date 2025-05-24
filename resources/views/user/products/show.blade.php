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
                            class="size-option px-3 py-1 rounded-full text-sm font-medium border
                            {{ $size['available'] ? 'bg-blue-100 text-blue-700 border-blue-300 hover:bg-blue-200' : 'bg-gray-200 text-gray-500 border-gray-300 cursor-not-allowed line-through' }}"
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

    {{-- Product Reviews --}}
    <div class="mt-10">
        <h2 class="text-2xl font-bold mb-4">Ulasan Produk</h2>

        @php
            $hasPurchased = Auth::check() && isset($product) && Auth::user()->transactions()
                ->where('status', 'paid')
                ->whereHas('products', fn($q) => $q->where('product_id', $product->id))
                ->exists();
        @endphp

        @auth
            @if ($hasPurchased)
                <form action="{{ route('products.review.store', $product->id) }}" method="POST" class="mb-6 space-y-4">
                    @csrf
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                        <select name="rating" id="rating" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="">Pilih Rating</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} - {{ ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'][$i - 1] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="review" class="block text-sm font-medium text-gray-700">Ulasan</label>
                        <textarea name="review" id="review" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required minlength="5"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Kirim Ulasan
                    </button>
                </form>
            @else
                <p class="text-gray-500">Anda hanya dapat memberikan ulasan untuk produk yang telah dibeli.</p>
            @endif
        @else
            <p class="text-gray-500">Silakan <a href="{{ route('login') }}" class="text-blue-500 hover:underline">login</a> untuk memberikan ulasan.</p>
        @endauth

        {{-- Show Reviews --}}
        <div class="mt-6">
            @forelse ($product->reviews as $review)
                <div class="mb-4 border-b pb-4">
                    <p class="text-sm text-gray-600">{{ $review->user->name }} - {{ $review->created_at->format('d M Y') }}</p>
                    <p class="text-yellow-500">
                        {!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}
                    </p>
                    <p class="text-gray-700">{{ $review->review }}</p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
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

@endsection

@section('scripts')
<script>
    const sizeButtons = document.querySelectorAll('.size-option');
    const selectedSizeIdInput = document.getElementById('formSizeId');
    const sizeError = document.getElementById('sizeError');

    sizeButtons.forEach(button => {
        button.addEventListener('click', () => {
            if(button.disabled) return;
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

    // Qty increment / decrement
    document.querySelectorAll('.increment-btn').forEach(button => {
        button.addEventListener('click', () => {
            const input = button.previousElementSibling;
            let max = parseInt(input.max) || 100;
            let val = parseInt(input.value) || 1;
            if(val < max) input.value = val + 1;
        });
    });
    document.querySelectorAll('.decrement-btn').forEach(button => {
        button.addEventListener('click', () => {
            const input = button.nextElementSibling;
            let val = parseInt(input.value) || 1;
            if(val > 1) input.value = val - 1;
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
