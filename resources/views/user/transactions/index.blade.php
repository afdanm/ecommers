@extends('layouts.home')

@section('content')

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Riwayat Transaksi</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($transactions->isEmpty())
        <p class="text-gray-500">Belum ada transaksi yang dilakukan.</p>
    @else
        <div class="space-y-4">
            @foreach ($transactions as $transaction)
                <div class="bg-white p-4 rounded shadow {{ $transaction->status == 'paid' ? 'border-l-4 border-green-500' : 'border-l-4 border-yellow-500' }}">
                    <h2 class="text-xl font-semibold">Transaksi #{{ $transaction->id }}</h2>
                    <p>Status: 
                        @if($transaction->status == 'paid')
                            <span class="text-green-600 font-semibold">LUNAS</span>
                        @else
                            <span class="text-red-600">Belum Dibayar</span>
                        @endif
                    </p>
                    <p>Total Harga: Rp {{ number_format($transaction->total_price) }}</p>
                    <p>Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</p>

                    <!-- Status Pengiriman -->
                    <div class="mt-2">
<p>Status Pengiriman: 
    @if ($transaction->purchase_method === 'delivery')
        <span class="text-blue-600">{{ ucfirst($transaction->shipping_status) }}</span>
    @else
        <span class="text-purple-600">{{ 'Ambil di Tempat' }}</span>
    @endif
</p>

                    </div>
                    @if ($transaction->status == 'paid' && $transaction->products->isNotEmpty())
                        <a href="{{ route('products.show', $transaction->products->first()->id) }}" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block mt-2">
                            Beri Ulasan
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
