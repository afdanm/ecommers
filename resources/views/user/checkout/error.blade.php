@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4 text-center">
    <h1 class="text-2xl font-bold text-red-600 mb-4">Pembayaran Gagal!</h1>
    <p class="text-lg mb-6">Maaf, terjadi kesalahan saat memproses pembayaran kamu.</p>
    <a href="{{ route('checkout.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Coba Lagi</a>
</div>
@endsection
