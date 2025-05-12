@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Keranjang Belanja</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($carts->count() > 0)
        <table class="w-full border text-left mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Produk</th>
                    <th class="p-2">Harga</th>
                    <th class="p-2">Jumlah</th>
                    <th class="p-2">Total</th>
                    <th class="p-2">Stok Tersedia</th>
                    <th class="p-2"></th>
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
                        <td class="p-2">{{ $cart->product->name }}</td>
                        <td class="p-2">Rp {{ number_format($cart->product->price) }}</td>
                        <td class="p-2">{{ $cart->quantity }}</td>
                        <td class="p-2">Rp {{ number_format($total) }}</td>
                        <td class="p-2">{{ $cart->product->stock }}</td>
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

        <div class="flex justify-between items-center">
            <p class="text-xl font-bold">Total: Rp {{ number_format($grandTotal) }}</p>
            <a href="{{ route('checkout.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Checkout</a>
        </div>

    @else
        <p class="text-gray-600">Keranjang kamu kosong.</p>
    @endif
</div>
@endsection