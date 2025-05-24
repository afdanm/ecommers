@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Keranjang Belanja</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($carts->count() > 0)
        <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
            <table class="w-full border text-left mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">
                            <input type="checkbox" id="select-all">
                    </th>
                    <th class="p-2">Produk</th>
                    <th class="p-2">Harga</th>
                    <th class="p-2">Jumlah</th>
                    <th class="p-2">Total</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($carts as $cart)
                    @php 
                            $total = $cart->product->price * $cart->quantity;
                            $grandTotal += $total;
                        @endphp
                    <tr>
                         <td class="p-2">
                                <input type="checkbox" name="cart_ids[]" value="{{ $cart->id }}" class="select-item">
                            </td>
                        <td class="p-2">{{ $cart->product->name }}</td>
                        <td class="p-2">Rp {{ number_format($cart->product->price) }}</td>
                        <td class="p-2 flex items-center space-x-2">
                            <!-- Form untuk kurangi quantity -->
                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="{{ max($cart->quantity - 1, 1) }}">
                                <input type="hidden" name="action" value="decrement">
                                <button type="submit" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300" 
                                    @if($cart->quantity <= 1) disabled @endif>-</button>
                            </form>

                            <span>{{ $cart->quantity }}</span>

                            <!-- Form untuk tambah quantity -->
                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="{{ $cart->quantity + 1 }}">
                                <input type="hidden" name="action" value="increment">
                                <button type="submit" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">+</button>
                            </form>
                        </td>
                        <td class="p-2">Rp {{ number_format($total) }}</td>
                        <td class="p-2">
                            <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       <div class="flex justify-between items-center mb-4">
                <p class="text-xl font-bold">Grand Total: Rp {{ number_format($grandTotal) }}</p>
                <button 
                    type="submit" 
                    id="btn-checkout" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                    disabled>
                    Checkout
                </button>
            </div>
        </form>

        <p class="text-sm text-gray-600">Centang kotak di kiri untuk memilih item mana yang akan di‐checkout.</p>

    @else
        <p class="text-gray-600">Keranjang kamu kosong.</p>
    @endif
</div>

<script>
    // Toggle tombol Checkout
    const selectAll = document.getElementById('select-all');
    const items = document.querySelectorAll('.select-item');
    const btn = document.getElementById('btn-checkout');

    function updateButton() {
        btn.disabled = ![...items].some(i => i.checked);
    }

    selectAll.addEventListener('change', () => {
        items.forEach(i => i.checked = selectAll.checked);
        updateButton();
    });

    items.forEach(i => i.addEventListener('change', () => {
        updateButton();
        if (!i.checked) selectAll.checked = false;
        else if ([...items].every(j => j.checked)) selectAll.checked = true;
    }));
</script>
@endsection
