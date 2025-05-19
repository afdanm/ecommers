@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-4">Tambah Produk</h1>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-input w-full" required>
    </div>

    <div class="mb-4">
        <label>Kategori</label>
        <select name="category_id" class="form-select w-full" required>
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label>Harga</label>
        <input type="number" name="price" class="form-input w-full" required>
    </div>

    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="form-textarea w-full"></textarea>
    </div>

    <div class="mb-4">
        <label>Gambar</label>
        <input type="file" name="image" class="form-input w-full">
    </div>

    <div class="mb-4">
        <label>Jenis Size</label>
        <select name="size_type" id="sizeType" class="form-select w-full" required>
            <option value="">Pilih Jenis Size</option>
            <option value="huruf">Huruf (XS - XXL)</option>
            <option value="angka">Angka (35 - 50)</option>
        </select>
    </div>

    <div id="sizeOptions" class="mb-4 hidden">
        <label>Pilih Size dan Input Stok</label>
        <div id="sizesContainer" class="grid grid-cols-2 gap-2 mt-2">
            {{-- Akan diisi dengan JavaScript --}}
        </div>
    </div>

    <div class="mb-4">
        <label>Total Stok</label>
        <input type="number" name="total_stock_display" id="totalStock" class="form-input w-full bg-gray-100" readonly>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
    const letterSizes = @json($letterSizes);
    const numberSizes = @json($numberSizes);

    const sizeTypeSelect = document.getElementById('sizeType');
    const sizesContainer = document.getElementById('sizesContainer');
    const sizeOptions = document.getElementById('sizeOptions');
    const totalStockInput = document.getElementById('totalStock');

    sizeTypeSelect.addEventListener('change', function () {
        const type = this.value;
        let sizes = type === 'huruf' ? letterSizes : numberSizes;

        sizesContainer.innerHTML = '';
        totalStockInput.value = 0;

        if (type) {
            sizeOptions.classList.remove('hidden');
            sizes.forEach(size => {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="sizes[]" value="${size.id}" onchange="toggleStockInput(this)">
                        ${size.name}
                    </label>
                    <input type="number" name="stocks[]" data-size="${size.id}" class="form-input w-full mt-1 stock-input hidden" placeholder="Stok" oninput="updateTotalStock()" min="0" value="0">
                `;
                sizesContainer.appendChild(wrapper);
            });
        } else {
            sizeOptions.classList.add('hidden');
        }
    });

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
</script>
@endsection
