@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-4">Edit Produk</h1>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-input w-full" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="mb-4">
        <label>Kategori</label>
        <select name="category_id" class="form-select w-full" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label>Harga</label>
        <input type="number" name="price" class="form-input w-full" value="{{ old('price', $product->price) }}" required>
    </div>

    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="form-textarea w-full">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label>Gambar (Kosongkan jika tidak diubah)</label>
        <input type="file" name="image" class="form-input w-full">
    </div>

    <div class="mb-4">
        <label>Jenis Size</label>
        <select name="size_type" id="sizeType" class="form-select w-full" required>
            <option value="">Pilih Jenis Size</option>
            <option value="huruf" {{ $product->size_type == 'huruf' ? 'selected' : '' }}>Huruf (XS - XXL)</option>
            <option value="angka" {{ $product->size_type == 'angka' ? 'selected' : '' }}>Angka (35 - 50)</option>
        </select>
    </div>

    <div id="sizeOptions" class="mb-4">
        <label>Pilih Size dan Input Stok</label>
        <div id="sizesContainer" class="grid grid-cols-2 gap-2 mt-2">
            {{-- Akan diisi JS --}}
        </div>
    </div>

    <div class="mb-4">
        <label>Total Stok</label>
        <input type="number" name="total_stock_display" id="totalStock" class="form-input w-full bg-gray-100" readonly>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script>
    const letterSizes = @json($letterSizes);
    const numberSizes = @json($numberSizes);
    const selectedType = "{{ $product->size_type }}";
    const selectedSizes = @json($product->sizes->pluck('id')->toArray());
    const selectedStocks = @json($product->sizes->pluck('pivot.stock', 'id'));

    const sizeTypeSelect = document.getElementById('sizeType');
    const sizesContainer = document.getElementById('sizesContainer');
    const totalStockInput = document.getElementById('totalStock');

    function renderSizes(type) {
        let sizes = type === 'huruf' ? letterSizes : numberSizes;
        sizesContainer.innerHTML = '';
        totalStockInput.value = 0;

        sizes.forEach(size => {
            const isChecked = selectedSizes.includes(size.id);
            const stock = selectedStocks[size.id] ?? 0;

            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="sizes[]" value="${size.id}" ${isChecked ? 'checked' : ''} onchange="toggleStockInput(this)">
                    ${size.name}
                </label>
                <input type="number" name="stocks[]" data-size="${size.id}" class="form-input w-full mt-1 stock-input ${isChecked ? '' : 'hidden'}" placeholder="Stok" oninput="updateTotalStock()" min="0" value="${stock}">
            `;
            sizesContainer.appendChild(wrapper);
        });

        updateTotalStock();
    }

    function toggleStockInput(checkbox) {
        const stockInput = checkbox.closest('div').querySelector('.stock-input');
        stockInput.classList.toggle('hidden', !checkbox.checked);
        if (!checkbox.checked) {
            stockInput.value = 0;
            updateTotalStock();
        }
    }

    function updateTotalStock() {
        let total = 0;
        document.querySelectorAll('.stock-input:not(.hidden)').forEach(input => {
            const val = parseInt(input.value) || 0;
            total += val;
        });
        totalStockInput.value = total;
    }

    sizeTypeSelect.addEventListener('change', function () {
        renderSizes(this.value);
    });

    // Init on load
    renderSizes(selectedType);
</script>
@endsection
