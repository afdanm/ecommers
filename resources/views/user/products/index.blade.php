@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>

    {{-- Dropdown Filter Kategori --}}
    <form method="GET" action="{{ route('products.list') }}" class="mb-4">
        <label for="category_id" class="mr-2">Filter berdasarkan kategori:</label>
        <select name="category_id" id="category_id" onchange="this.form.submit()" class="border px-2 py-1 rounded">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($products as $product)
            <a href="{{ route('products.show', $product->id) }}" class="border rounded p-4 hover:shadow flex flex-col">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-32 w-full object-cover mb-2">
                <h2 class="font-semibold">{{ $product->name }}</h2>
                <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                
                {{-- Size Information --}}
                @if($product->sizes->count() > 0)
                    <div class="mt-1 mb-1">
                        <div class="flex flex-wrap gap-1">
                            @foreach($product->availableSizes->take(3) as $size)
                                <span class="text-xs px-1 py-0.5 bg-gray-100 rounded border border-gray-200">
                                    {{ $size['name'] }} ({{ $size['stock'] }})
                                </span>
                            @endforeach
                            @if($product->availableSizes->count() > 3)
                                <span class="text-xs text-gray-500">+{{ $product->availableSizes->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-500 mt-1">Stok: {{ $product->stock }}</p>
                @endif
                
                <p class="text-green-600 font-bold mt-auto">Rp {{ number_format($product->price) }}</p>
                <p class="text-sm text-yellow-500">
                    @if ($product->reviews->count() > 0)
                        {{ number_format($product->reviews->avg('rating'), 1) }} / 5
                        ({{ $product->reviews->count() }} ulasan)
                    @else
                        Belum ada ulasan
                    @endif
                </p>
            </a>
        @endforeach
    </div>
</div>
@endsection