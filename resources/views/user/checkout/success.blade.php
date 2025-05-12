@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4 text-center">
    <h1 class="text-2xl font-bold text-green-600 mb-4">Pembayaran Berhasil!</h1>
    <p class="text-lg mb-6">Terima kasih, pembayaran kamu sudah kami terima.</p>
    <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Kembali ke Beranda</a>
</div>
@endsection
