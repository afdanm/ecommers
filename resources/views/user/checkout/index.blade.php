{{-- resources/views/user/checkout/index.blade.php --}}
@extends('layouts.home')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
        @csrf

        <!-- List Produk -->
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Produk</h2>
            <ul>
                @foreach($carts as $cart)
                    <li class="flex justify-between mb-2">
                        <span>{{ $cart->product->name }} x {{ $cart->quantity }}</span>
                        <span>Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <hr class="my-2">
            <div class="flex justify-between font-semibold">
                <span>Total</span>
                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metode Pembelian -->
        <div class="bg-white rounded shadow p-4">
            <label for="purchase_method" class="block font-medium mb-2">Metode Pembelian</label>
            <select id="purchase_method" name="purchase_method" class="w-full border rounded p-2" required>
                <option value="pickup" {{ old('purchase_method') == 'pickup' ? 'selected' : '' }}>Ambil di Toko</option>
                <option value="delivery" {{ old('purchase_method') == 'delivery' ? 'selected' : '' }}>Kirim ke Alamat</option>
            </select>
        </div>

        <!-- Alamat jika delivery -->
        <div id="address-section" class="bg-white rounded shadow p-4 {{ old('purchase_method') == 'delivery' ? '' : 'hidden' }}">
            <label for="delivery_address" class="block font-medium mb-2">Alamat Pengiriman</label>
            <textarea name="delivery_address" id="delivery_address" rows="3" class="w-full border rounded p-2">{{ old('delivery_address') }}</textarea>
            @error('delivery_address')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Lanjutkan ke Pembayaran
        </button>
    </form>
</div>

<script>
    function toggleAddress() {
        const method = document.getElementById('purchase_method').value;
        const addressSection = document.getElementById('address-section');
        if (method === 'delivery') {
            addressSection.classList.remove('hidden');
        } else {
            addressSection.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleAddress();
        document.getElementById('purchase_method').addEventListener('change', toggleAddress);
    });
</script>
@endsection
