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
            @if (Auth::check())
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
            @endif
        </div>
    </div>
</div>

{{-- Login Modal --}}
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded p-6 max-w-sm text-center">
        <h2 class="text-xl font-bold mb-4">Silakan Login atau Register</h2>
        <p class="mb-4">Untuk menambahkan produk ke keranjang, kamu harus login dulu.</p>
        <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Login</a>
        <a href="{{ route('register') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Register</a>
        <button onclick="closeLoginModal()" class="block mt-4 text-red-500 hover:underline">Tutup</button>
    </div>
</div>

<script>
    // Quantity Button Handler
    document.querySelectorAll('.increment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[name="qty"]');
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[name="qty"]');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    // Modal Functions
    function openLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
    }
</script>
@endsection