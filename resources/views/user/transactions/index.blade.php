@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Riwayat Transaksi</h1>

    @if ($transactions->isEmpty())
        <p>Belum ada transaksi yang dilakukan.</p>
    @else
        <div class="space-y-4">
            @foreach ($transactions as $transaction)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold">Transaksi #{{ $transaction->id }}</h2>
                    <p>Status: {{ ucfirst($transaction->status) }}</p>
                    <p>Total Harga: Rp {{ number_format($transaction->total_price) }}</p>
                    <p>Tanggal: {{ $transaction->created_at->format('d M Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
