@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <div class="grid md:grid-cols-2 gap-6">
        {{-- Product Image --}}
        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full rounded shadow">

        {{-- Product Detail --}}
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-1">Kategori: <strong>{{ $product->category->name }}</strong></p>
            <p class="text-green-600 text-2xl font-bold mb-4">Rp {{ number_format($product->price) }}</p>
            <p class="mb-2">Stok: <strong>{{ $product->stock }}</strong></p>
            <p class="mb-4">{{ $product->description }}</p>

            {{-- Add to Cart / Login Modal --}}
            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex items-center space-x-4">
                    @csrf
                    <div class="flex items-center border border-gray-300 rounded">
                        <button type="button" class="px-3 py-1 decrement-btn" {{ $product->stock <= 1 ? 'disabled' : '' }}>-</button>
                        <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center border-x border-gray-300 py-1" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <button type="button" class="px-3 py-1 increment-btn" {{ $product->stock <= 1 ? 'disabled' : '' }}>+</button>
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        Masukkan ke Keranjang
                    </button>
                </form>

                @if(session('error'))
                    <div class="mt-4 text-red-500">
                        {{ session('error') }}
                    </div>
                @endif
            @else
                <button onclick="openLoginModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Masukkan ke Keranjang
                </button>
            @endauth
        </div>
    </div>

    {{-- Review Section --}}
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Ulasan Produk</h2>

    @php
        $hasPurchased = Auth::check() && isset($product) && Auth::user()->transactions()
            ->where('status', 'paid') // Hanya transaksi dengan status 'paid'
            ->whereHas('products', fn($q) => $q->where('product_id', $product->id))
            ->exists();
    @endphp

        @auth
            @if ($hasPurchased)
                <form action="{{ route('products.review.store', $product->id) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                        <select name="rating" id="rating" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Pilih Rating</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} - {{ ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'][$i - 1] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="review" class="block text-sm font-medium text-gray-700">Ulasan</label>
                        <textarea name="review" id="review" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required minlength="5"></textarea>
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

        {{-- Display Existing Reviews --}}
        <div class="mt-6">
            @if ($product->reviews->isEmpty())
                <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
            @else
                @foreach ($product->reviews as $review)
                    <div class="mb-4 border-b pb-4">
                        <p class="text-sm text-gray-600">{{ $review->user->name }} - {{ $review->created_at->format('d M Y') }}</p>
                        <p class="text-yellow-500">
                            {!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}
                        </p>
                        <p class="text-gray-700">{{ $review->review }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Login Modal --}}
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white rounded p-6 max-w-sm text-center mx-auto mt-32">
        <h2 class="text-xl font-bold mb-4">Silakan Login atau Register</h2>
        <p class="mb-4">Untuk menambahkan produk ke keranjang, kamu harus login dulu.</p>
        <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Login</a>
        <a href="{{ route('register') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Register</a>
        <button onclick="closeLoginModal()" class="block mt-4 text-red-500 hover:underline">Tutup</button>
    </div>
</div>

{{-- Scripts --}}
<script>
    document.querySelectorAll('.increment-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentNode.querySelector('input[name="qty"]');
            const max = parseInt(input.max);
            let value = parseInt(input.value) || 1;
            if (value < max) input.value = value + 1;
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentNode.querySelector('input[name="qty"]');
            let value = parseInt(input.value) || 1;
            if (value > 1) input.value = value - 1;
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
