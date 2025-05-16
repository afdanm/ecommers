@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Produk</h1>
        <a href="{{ route('admin.products.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Foto Produk -->
            <div>
                @if($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" 
                         class="w-full h-auto rounded-md object-cover">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-md flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
            </div>

            <!-- Informasi Produk -->
            <div class="md:col-span-2 space-y-4">
                <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                <p class="text-gray-700">{{ $product->description }}</p>

                <p>
                    <span class="font-semibold">Kategori: </span>{{ $product->category->name }}
                </p>
                <p>
                    <span class="font-semibold">Harga: </span>Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <p>
                    <span class="font-semibold">Total Stok: </span>{{ $product->stock }}
                </p>

                <!-- Detail Stok per Ukuran -->
                @if($product->sizeStocks->count() > 0)
                    <div>
                        <h3 class="font-semibold mb-2">Stok per Ukuran:</h3>
                        <table class="min-w-full border border-gray-300 rounded-md">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Ukuran</th>
                                    <th class="border px-4 py-2 text-left">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->sizeStocks as $sizeStock)
                                <tr>
                                    <td class="border px-4 py-2">{{ $sizeStock->size->name }}</td>
                                    <td class="border px-4 py-2">{{ $sizeStock->stock }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 italic">Tidak ada data stok per ukuran.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
