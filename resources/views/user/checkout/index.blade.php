@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    <p class="mb-4 text-lg">Total Pembayaran: <strong>Rp {{ number_format($total) }}</strong></p>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Lanjutkan ke Pembayaran</button>
    </form>
</div>
@endsection
