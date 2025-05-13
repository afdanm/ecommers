@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Kategori Produk</label>
            <select name="category_id" class="w-full border rounded px-3 py-2">
                <option value="">Semua</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </div>
    </form>

    <!-- Summary of Sales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Total Transaksi</p>
            <h2 class="text-xl font-bold">{{ $totalTransactions }}</h2>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Total Produk Terjual</p>
            <h2 class="text-xl font-bold">{{ $totalItemsSold }}</h2>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Pendapatan</p>
            <h2 class="text-xl font-bold">Rp {{ number_format($totalRevenue) }}</h2>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Rata-rata Transaksi</p>
            <h2 class="text-xl font-bold">Rp {{ number_format($avgTransaction) }}</h2>
        </div>
    </div>

    <!-- Export Button -->
    <div class="mb-6">
        <a href="{{ route('admin.reports.sales.export', request()->all()) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Ekspor ke Excel
        </a>
    </div>

    <!-- Transaction Table -->
    <h2 class="text-lg font-semibold mb-2">Daftar Transaksi</h2>
    <div class="overflow-auto mb-8">
        <table class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Metode</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $transaction->id }}</td>
                        <td class="px-4 py-2">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($transaction->total_price) }}</td>
                        <td class="px-4 py-2">{{ ucfirst($transaction->purchase_method) }}</td>
                        <td class="px-4 py-2">
                            <span class="{{ $transaction->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Product Sales Table -->
    <h2 class="text-lg font-semibold mb-2">Produk Terjual</h2>
    <div class="overflow-auto">
        <table class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2">Nama Produk</th>
                    <th class="px-4 py-2">Kategori</th>
                    <th class="px-4 py-2">Jumlah Terjual</th>
                    <th class="px-4 py-2">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productSales as $product)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $product['name'] }}</td>
                        <td class="px-4 py-2">{{ $product['category'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $product['quantity'] }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($product['total']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
