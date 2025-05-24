{{-- resources/views/user/transactions/index.blade.php --}}
@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Riwayat Transaksi</h1>

    @if ($transactions->isEmpty())
        <div class="text-gray-500 text-center py-10">
            <p>Belum ada transaksi yang dilakukan.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($transactions as $transaction)
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-xl font-semibold">Transaksi #{{ $transaction->id }}</h2>
                        <span class="text-sm bg-green-100 text-green-700 px-2 py-1 rounded font-medium">LUNAS</span>
                    </div>

                    <div class="text-gray-700 space-y-1">
                        <p><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                        <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Metode Pembelian:</strong> {{ $transaction->purchase_method === 'pickup' ? 'Ambil di Tempat' : 'Dikirim ke Alamat' }}</p>

                        @if ($transaction->purchase_method === 'delivery')
                            <p><strong>Alamat Pengiriman:</strong> {{ $transaction->delivery_address }}</p>
                            <p><strong>Status Pengiriman:</strong> 
                                @php
                                    $status = $transaction->shipping_status ?? 'diproses';
                                    $labels = [
                                        'diproses' => 'Sedang Diproses',
                                        'dikirim' => 'Sedang Dikirim',
                                        'selesai' => 'Selesai'
                                    ];
                                @endphp
                                <span class="font-semibold">{{ $labels[$status] ?? $status }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
