@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<h1 class="text-xl font-bold mb-4">Edit Produk: {{ $product->name }}</h1>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Bagian Utama</h2>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                   class="form-input w-full border border-gray-300 rounded-md px-3 py-2" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select name="category_id" class="form-select w-full border border-gray-300 rounded-md px-3 py-2" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" class="form-textarea w-full border border-gray-300 rounded-md px-3 py-2" 
                      rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Foto Produk <span class="text-sm text-gray-500">(Maksimal 6 foto, rasio 1:1)</span>
            </label>
            
            <div class="mb-3">
                @php
                    // Safe way to handle images - check if it's already an array or needs decoding
                    $existingImages = [];
                    if ($product->images) {
                        if (is_array($product->images)) {
                            $existingImages = $product->images;
                        } elseif (is_string($product->images)) {
                            $existingImages = json_decode($product->images, true) ?? [];
                        }
                    }
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <span id="uploadedCount">{{ count($existingImages) }}</span> / 6 foto terupload
                </span>
            </div>

            <!-- Existing Images -->
            <div id="existingImagesContainer" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                @foreach($existingImages as $index => $imagePath)
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 existing-image" data-image-path="{{ $imagePath }}">
                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Existing {{ $index + 1 }}" 
                             class="w-full h-32 object-cover rounded cursor-pointer" 
                             onclick="openImageModal('{{ asset('storage/' . $imagePath) }}')">
                        <button type="button" onclick="removeExistingImage('{{ $imagePath }}', this)" 
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 transition-colors"
                                title="Hapus foto">
                            ×
                        </button>
                        <p class="text-xs text-gray-500 mt-1 text-center">Foto {{ $index + 1 }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <input 
                    type="file" 
                    name="images[]" 
                    id="imageUpload" 
                    class="form-input w-full border border-gray-300 rounded-md px-3 py-2" 
                    multiple 
                    accept="image/jpeg,image/png,image/gif"
                >
                <p class="text-xs text-gray-500 mt-1">Pilih hingga 6 foto sekaligus untuk menambah atau mengganti. Format: JPG, PNG, GIF. Maksimal 2MB per foto.</p>
            </div>
            
            <!-- Hidden input untuk menyimpan gambar yang akan dihapus -->
            <div id="deletedImagesContainer"></div>
            
            @error('images')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Hidden container untuk menyimpan file yang dipilih -->
            <div id="hiddenFilesContainer"></div>
            
            <div id="imagePreviewContainer" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                <!-- Preview new images will be inserted here -->
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informasi Produk</h2>
        
        <div class="mb-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="has_variants" id="hasVariants" 
                       class="form-checkbox h-5 w-5 text-blue-600" value="1" 
                       {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Gunakan Varian</span>
            </label>
        </div>

        <!-- Bagian untuk produk TANPA varian -->
        <div id="simpleProductFields" class="{{ old('has_variants', $product->has_variants) ? 'hidden' : '' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01"
                           class="form-input w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0"
                           class="form-input w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="0" step="0.01"
                           class="form-input w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('weight')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dimensi Paket (cm)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="number" name="length" placeholder="Panjang" value="{{ old('length', $product->length) }}" min="0" step="0.1"
                               class="form-input border border-gray-300 rounded-md px-3 py-2">
                        <input type="number" name="width" placeholder="Lebar" value="{{ old('width', $product->width) }}" min="0" step="0.1"
                               class="form-input border border-gray-300 rounded-md px-3 py-2">
                        <input type="number" name="height" placeholder="Tinggi" value="{{ old('height', $product->height) }}" min="0" step="0.1"
                               class="form-input border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    @error('length')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('width')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('height')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Bagian untuk produk DENGAN varian -->
        <div id="variantFields" class="{{ old('has_variants', $product->has_variants) ? '' : 'hidden' }}">
            <div id="variantContainer">
                <!-- Varian 1 -->
                <div class="mb-6 variant-section" data-variant-index="0">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Varian 1</label>
                    </div>
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="variant_names[]" placeholder="Nama varian (contoh: Motif)" 
                               class="form-input flex-1 border border-gray-300 rounded-md px-3 py-2" 
                               value="{{ old('variant_names.0', $variantNames[0] ?? '') }}">
                    </div>
                    
                    <div class="options-container mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Varian</label>
                        <div class="flex flex-wrap gap-2 mb-2 option-display-container">
                            @php
                                $variant0Options = old('variant_options.0', $variantOptions[0] ?? []);
                            @endphp
                            @if($variant0Options)
                                @foreach($variant0Options as $option)
                                    <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                                        <span class="mr-2">{{ $option }}</span>
                                        <button type="button" class="remove-option text-gray-500 hover:text-red-500">
                                            &times;
                                        </button>
                                        <input type="hidden" name="variant_options[0][]" value="{{ $option }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <input type="text" class="form-input flex-1 border border-gray-300 rounded-md px-3 py-2 new-option-input" 
                                   placeholder="Tambah opsi (contoh: Bunga)">
                            <button type="button" class="add-option-btn bg-blue-500 text-white px-3 py-2 rounded-md hover:bg-blue-600">
                                Tambah Opsi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Varian 2 (optional) -->
                <div class="mb-6 variant-section {{ empty($variantNames[1] ?? '') ? 'hidden' : '' }}" data-variant-index="1">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Varian 2</label>
                        <button type="button" class="remove-variant-btn text-red-500 hover:text-red-700 text-sm">
                            Hapus Varian
                        </button>
                    </div>
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="variant_names[]" placeholder="Nama varian (contoh: Ukuran)" 
                               class="form-input flex-1 border border-gray-300 rounded-md px-3 py-2" 
                               value="{{ old('variant_names.1', $variantNames[1] ?? '') }}">
                    </div>
                    
                    <div class="options-container mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Varian</label>
                        <div class="flex flex-wrap gap-2 mb-2 option-display-container">
                            @php
                                $variant1Options = old('variant_options.1', $variantOptions[1] ?? []);
                            @endphp
                            @if($variant1Options)
                                @foreach($variant1Options as $option)
                                    <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                                        <span class="mr-2">{{ $option }}</span>
                                        <button type="button" class="remove-option text-gray-500 hover:text-red-500">
                                            &times;
                                        </button>
                                        <input type="hidden" name="variant_options[1][]" value="{{ $option }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <input type="text" class="form-input flex-1 border border-gray-300 rounded-md px-3 py-2 new-option-input" 
                                   placeholder="Tambah opsi (contoh: XL)">
                            <button type="button" class="add-option-btn bg-blue-500 text-white px-3 py-2 rounded-md hover:bg-blue-600">
                                Tambah Opsi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Varian -->
            <div class="mb-6">
                <button type="button" id="addVariantBtn" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300 text-sm {{ !empty($variantNames[1] ?? '') ? 'hidden' : '' }}">
                    + Tambah Varian
                </button>
                <p class="text-xs text-gray-500 mt-1">Maksimal 2 varian</p>
            </div>

            <!-- Tabel Kombinasi Varian -->
            <div id="variantCombinationsContainer" class="{{ ($product->has_variants && $product->variants->count() > 0) ? '' : 'hidden' }}">
                <h3 class="text-md font-medium mb-3">Kombinasi Varian</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead id="variantTableHead">
                            <!-- Header akan di-generate oleh JavaScript -->
                        </thead>
                        <tbody id="variantCombinationsBody">
                            <!-- Kombinasi akan di-generate oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" id="submitBtn" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
            Update Produk
        </button>
        <a href="{{ route('admin.products.index') }}" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Batal
        </a>
    </div>
</form>

@if(session('success'))
    <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="errorAlert" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        {{ session('error') }}
    </div>
@endif

<script>
const maxImages = 6;
const maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
let uploadedImages = [];
let deletedImages = [];
let variantOptions = [[], []]; // Untuk menyimpan opsi varian
let existingVariants = @json($product->variants ?? []);
let fileCounter = 0;

// Initialize variant options from existing data or old input
@if(old('variant_options'))
    @foreach(old('variant_options') as $index => $options)
        @if($options)
            variantOptions[{{ $index }}] = @json($options);
        @endif
    @endforeach
@else
    @if(isset($variantOptions))
        @foreach($variantOptions as $index => $options)
            @if($options)
                variantOptions[{{ $index }}] = @json($options);
            @endif
        @endforeach
    @endif
@endif

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form state
    initializeForm();
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        if (successAlert) successAlert.style.display = 'none';
        if (errorAlert) errorAlert.style.display = 'none';
    }, 5000);
    
    // Update counter based on existing images
    updateUploadCounter();
});

// Initialize form based on existing data
function initializeForm() {
    const hasVariants = document.getElementById('hasVariants').checked;
    if (hasVariants) {
        generateVariantCombinations();
    }
}

// Remove existing image
function removeExistingImage(imagePath, button) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        // Add to deleted images array
        deletedImages.push(imagePath);
        
        // Add hidden input to track deleted images
        const deletedContainer = document.getElementById('deletedImagesContainer');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'deleted_images[]';
        hiddenInput.value = imagePath;
        deletedContainer.appendChild(hiddenInput);
        
        // Remove the image container
        button.closest('.existing-image').remove();
        
        // Update counter
        updateUploadCounter();
    }
}

// Update upload counter
function updateUploadCounter() {
    const existingImages = document.querySelectorAll('.existing-image').length;
    const newImages = uploadedImages.length;
    const totalImages = existingImages + newImages;
    document.getElementById('uploadedCount').textContent = totalImages;
}

// Toggle bagian varian
document.getElementById('hasVariants').addEventListener('change', function() {
    const hasVariants = this.checked;
    document.getElementById('simpleProductFields').classList.toggle('hidden', hasVariants);
    document.getElementById('variantFields').classList.toggle('hidden', !hasVariants);
    
    if (!hasVariants) {
        document.getElementById('variantCombinationsContainer').classList.add('hidden');
        // Reset variant data
        resetVariantData();
    } else {
        generateVariantCombinations();
    }
});

// Reset variant data
function resetVariantData() {
    variantOptions = [[], []];
    document.querySelectorAll('.variant-section').forEach(section => {
        const index = parseInt(section.dataset.variantIndex);
        if (index > 0) {
            section.classList.add('hidden');
        }
        section.querySelector('input[name="variant_names[]"]').value = '';
        section.querySelector('.option-display-container').innerHTML = '';
    });
    document.getElementById('addVariantBtn').style.display = 'inline-block';
}

// Menangani penambahan opsi varian
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-option-btn')) {
        const variantSection = e.target.closest('.variant-section');
        const variantIndex = parseInt(variantSection.dataset.variantIndex);
        const input = e.target.previousElementSibling;
        const option = input.value.trim();
        
        if (!option) {
            alert('Masukkan nama opsi terlebih dahulu.');
            return;
        }
        
        // Cek duplikasi
        if (variantOptions[variantIndex].includes(option)) {
            alert('Opsi sudah ada!');
            return;
        }
        
        // Tambahkan ke array
        variantOptions[variantIndex].push(option);
        
        // Update tampilan
        addOptionToDisplay(variantSection, variantIndex, option);
        
        // Reset input
        input.value = '';
        
        // Generate kombinasi jika ada varian yang diisi
        generateVariantCombinations();
    }
    
    // Menangani penghapusan opsi varian
    if (e.target.classList.contains('remove-option')) {
        const variantSection = e.target.closest('.variant-section');
        const variantIndex = parseInt(variantSection.dataset.variantIndex);
        const optionElement = e.target.closest('.flex.items-center');
        const option = optionElement.querySelector('span').textContent.trim();
        
        // Hapus dari array
        variantOptions[variantIndex] = variantOptions[variantIndex].filter(opt => opt !== option);
        
        // Hapus elemen
        optionElement.remove();
        
        // Generate ulang kombinasi
        generateVariantCombinations();
    }
});

// Add option to display
function addOptionToDisplay(variantSection, variantIndex, option) {
    const optionsContainer = variantSection.querySelector('.option-display-container');
    const optionElement = document.createElement('div');
    optionElement.className = 'flex items-center bg-gray-100 rounded-full px-3 py-1';
    optionElement.innerHTML = `
        <span class="mr-2">${option}</span>
        <button type="button" class="remove-option text-gray-500 hover:text-red-500">
            &times;
        </button>
        <input type="hidden" name="variant_options[${variantIndex}][]" value="${option}">
    `;
    optionsContainer.appendChild(optionElement);
}

// Allow Enter key to add options
document.addEventListener('keypress', function(e) {
    if (e.target.classList.contains('new-option-input') && e.key === 'Enter') {
        e.preventDefault();
        const addBtn = e.target.nextElementSibling;
        if (addBtn && addBtn.classList.contains('add-option-btn')) {
            addBtn.click();
        }
    }
});

// Menambahkan varian baru
document.getElementById('addVariantBtn').addEventListener('click', function() {
    const hiddenVariant = document.querySelector('.variant-section[data-variant-index="1"]');
    const visibleVariants = document.querySelectorAll('.variant-section:not(.hidden)');
    
    if (visibleVariants.length < 2 && hiddenVariant) {
        hiddenVariant.classList.remove('hidden');
        this.style.display = 'none';
        generateVariantCombinations();
    }
});

// Menghapus varian
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant-btn')) {
        const variantSection = e.target.closest('.variant-section');
        const variantIndex = parseInt(variantSection.dataset.variantIndex);
        
        // Reset opsi varian
        variantOptions[variantIndex] = [];
        
        // Sembunyikan section
        variantSection.classList.add('hidden');
        
        // Reset input
        const nameInput = variantSection.querySelector('input[name="variant_names[]"]');
        if (nameInput) nameInput.value = '';
        
        const optionContainer = variantSection.querySelector('.option-display-container');
        if (optionContainer) optionContainer.innerHTML = '';
        
        // Tampilkan tombol tambah varian
        document.getElementById('addVariantBtn').style.display = 'inline-block';
        
        // Generate ulang kombinasi
        generateVariantCombinations();
    }
});

// Fungsi untuk generate kombinasi varian
function generateVariantCombinations() {
    const hasVariants = document.getElementById('hasVariants').checked;
    if (!hasVariants) return;
    
    // Hanya generate jika ada opsi di varian pertama
    if (variantOptions[0].length === 0) {
        document.getElementById('variantCombinationsContainer').classList.add('hidden');
        return;
    }
    
    // Get variant names
    const variantNames = [];
    const visibleVariantSections = document.querySelectorAll('.variant-section:not(.hidden)');
    
    visibleVariantSections.forEach(section => {
        const nameInput = section.querySelector('input[name="variant_names[]"]');
        if (nameInput && nameInput.value.trim()) {
            variantNames.push(nameInput.value.trim());
        }
    });
    
    if (variantNames.length === 0) {
        document.getElementById('variantCombinationsContainer').classList.add('hidden');
        return;
    }
    
    // Generate kombinasi
    let combinations = [];
    
    if (variantOptions[1].length > 0 && variantNames.length > 1) {
        // Kombinasi untuk 2 varian
        for (const option1 of variantOptions[0]) {
            for (const option2 of variantOptions[1]) {
                combinations.push([option1, option2]);
            }
        }
    } else {
        // Hanya varian 1
        combinations = variantOptions[0].map(option => [option]);
    }
    
    // Update header tabel
    generateTableHeader(variantNames);
    
    // Update body tabel
    generateTableBody(combinations);
    
    document.getElementById('variantCombinationsContainer').classList.remove('hidden');
}

// Generate table header
function generateTableHeader(variantNames) {
    const thead = document.getElementById('variantTableHead');
    thead.innerHTML = '';
    
    const headerRow = document.createElement('tr');
    headerRow.className = 'bg-gray-50';
    
    // Kolom foto
    const thImage = document.createElement('th');
    thImage.className = 'px-4 py-2 border-b font-medium text-left';
    thImage.textContent = 'Foto';
    headerRow.appendChild(thImage);
    
    // Kolom varian
    variantNames.forEach(name => {
        const th = document.createElement('th');
        th.className = 'px-4 py-2 border-b font-medium text-left';
        th.textContent = name;
        headerRow.appendChild(th);
    });
    
    // Kolom data produk
    const productFields = ['Harga', 'Stok', 'Berat (kg)', 'Panjang (cm)', 'Lebar (cm)', 'Tinggi (cm)'];
    productFields.forEach(label => {
        const th = document.createElement('th');
        th.className = 'px-4 py-2 border-b font-medium text-left';
        th.textContent = label;
        headerRow.appendChild(th);
    });
    
    thead.appendChild(headerRow);
}

// Generate table body with existing variant data
function generateTableBody(combinations) {
    const tbody = document.getElementById('variantCombinationsBody');
    tbody.innerHTML = '';
    
    combinations.forEach((combination, index) => {
        const tr = document.createElement('tr');
        tr.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
        
        // Find existing variant data for this combination
        let existingVariant = null;
        if (existingVariants && existingVariants.length > 0) {
            existingVariant = existingVariants.find(variant => {
                if (combination.length === 1) {
                    return variant.variant_1 === combination[0];
                } else if (combination.length === 2) {
                    return variant.variant_1 === combination[0] && variant.variant_2 === combination[1];
                }
                return false;
            });
        }
        
       // Continuation from where the code was cut off...

        // Kolom foto
        const tdImage = document.createElement('td');
        tdImage.className = 'px-4 py-2 border-b';
        
        const imageInput = document.createElement('input');
        imageInput.type = 'file';
        imageInput.name = `variant_images[${index}]`;
        imageInput.className = 'form-input w-full border border-gray-300 rounded-md px-2 py-1 text-sm';
        imageInput.accept = 'image/jpeg,image/png,image/gif';
        
        // Show existing image if available
        if (existingVariant && existingVariant.image) {
            const existingImageContainer = document.createElement('div');
            existingImageContainer.className = 'mb-2';
            
            const existingImage = document.createElement('img');
            existingImage.src = `/storage/${existingVariant.image}`;
            existingImage.className = 'w-16 h-16 object-cover rounded border';
            existingImage.alt = 'Variant Image';
            
            existingImageContainer.appendChild(existingImage);
            tdImage.appendChild(existingImageContainer);
        }
        
        tdImage.appendChild(imageInput);
        tr.appendChild(tdImage);
        
        // Kolom varian
        combination.forEach((option, optionIndex) => {
            const td = document.createElement('td');
            td.className = 'px-4 py-2 border-b font-medium';
            td.textContent = option;
            
            // Hidden inputs untuk varian
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `variants[${index}][variant_${optionIndex + 1}]`;
            hiddenInput.value = option;
            td.appendChild(hiddenInput);
            
            tr.appendChild(td);
        });
        
        // Kolom data produk dengan nilai dari existing variant jika ada
        const productFields = [
            { name: 'price', type: 'number', step: '0.01', min: '0', defaultValue: existingVariant?.price || '' },
            { name: 'stock', type: 'number', min: '0', defaultValue: existingVariant?.stock || '0' },
            { name: 'weight', type: 'number', step: '0.01', min: '0', defaultValue: existingVariant?.weight || '' },
            { name: 'length', type: 'number', step: '0.1', min: '0', defaultValue: existingVariant?.length || '' },
            { name: 'width', type: 'number', step: '0.1', min: '0', defaultValue: existingVariant?.width || '' },
            { name: 'height', type: 'number', step: '0.1', min: '0', defaultValue: existingVariant?.height || '' }
        ];
        
        productFields.forEach(field => {
            const td = document.createElement('td');
            td.className = 'px-4 py-2 border-b';
            
            const input = document.createElement('input');
            input.type = field.type;
            input.name = `variants[${index}][${field.name}]`;
            input.className = 'form-input w-full border border-gray-300 rounded-md px-2 py-1';
            input.value = field.defaultValue;
            
            if (field.step) input.step = field.step;
            if (field.min) input.min = field.min;
            
            // Add hidden input for variant ID if editing existing variant
            if (existingVariant && existingVariant.id) {
                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = `variants[${index}][id]`;
                hiddenId.value = existingVariant.id;
                td.appendChild(hiddenId);
            }
            
            td.appendChild(input);
            tr.appendChild(td);
        });
        
        tbody.appendChild(tr);
    });
}

// Handle image upload with preview
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const container = document.getElementById('imagePreviewContainer');
    const hiddenContainer = document.getElementById('hiddenFilesContainer');
    
    // Clear previous previews and hidden inputs
    container.innerHTML = '';
    hiddenContainer.innerHTML = '';
    uploadedImages = [];
    
    // Check total images (existing + new)
    const existingCount = document.querySelectorAll('.existing-image').length;
    const totalWillBe = existingCount + files.length;
    
    if (totalWillBe > maxImages) {
        alert(`Maksimal ${maxImages} foto. Anda sudah memiliki ${existingCount} foto, hanya dapat menambah ${maxImages - existingCount} foto lagi.`);
        this.value = '';
        return;
    }
    
    files.forEach((file, index) => {
        // Validate file size
        if (file.size > maxFileSize) {
            alert(`File ${file.name} terlalu besar. Maksimal 2MB per foto.`);
            return;
        }
        
        // Validate file type
        if (!file.type.match(/^image\/(jpeg|png|gif)$/)) {
            alert(`File ${file.name} bukan format gambar yang didukung.`);
            return;
        }
        
        uploadedImages.push(file);
        
        // Create hidden file input for form submission
        const hiddenFileInput = document.createElement('input');
        hiddenFileInput.type = 'file';
        hiddenFileInput.name = `images[${fileCounter}]`;
        hiddenFileInput.style.display = 'none';
        
        // Transfer the file to hidden input
        const dt = new DataTransfer();
        dt.items.add(file);
        hiddenFileInput.files = dt.files;
        
        hiddenContainer.appendChild(hiddenFileInput);
        fileCounter++;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50';
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-32 object-cover rounded cursor-pointer';
            img.onclick = () => openImageModal(e.target.result);
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 transition-colors';
            removeBtn.innerHTML = '×';
            removeBtn.title = 'Hapus foto';
            removeBtn.onclick = () => removeNewImage(previewDiv, hiddenFileInput);
            
            const label = document.createElement('p');
            label.className = 'text-xs text-gray-500 mt-1 text-center';
            label.textContent = `Foto Baru ${index + 1}`;
            
            previewDiv.appendChild(img);
            previewDiv.appendChild(removeBtn);
            previewDiv.appendChild(label);
            
            container.appendChild(previewDiv);
        };
        
        reader.readAsDataURL(file);
    });
    
    // Update counter
    updateUploadCounter();
});

// Remove new image from preview
function removeNewImage(previewDiv, hiddenInput) {
    previewDiv.remove();
    hiddenInput.remove();
    
    // Update uploadedImages array
    const fileName = hiddenInput.files[0].name;
    uploadedImages = uploadedImages.filter(file => file.name !== fileName);
    
    updateUploadCounter();
}

// Open image modal for full view
function openImageModal(imageSrc) {
    // Create modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.onclick = (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    };
    
    const modalContent = document.createElement('div');
    modalContent.className = 'max-w-4xl max-h-4xl p-4';
    
    const img = document.createElement('img');
    img.src = imageSrc;
    img.className = 'max-w-full max-h-full object-contain rounded';
    
    const closeBtn = document.createElement('button');
    closeBtn.className = 'absolute top-4 right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center text-xl hover:bg-gray-200';
    closeBtn.innerHTML = '×';
    closeBtn.onclick = () => document.body.removeChild(modal);
    
    modalContent.appendChild(img);
    modal.appendChild(modalContent);
    modal.appendChild(closeBtn);
    
    document.body.appendChild(modal);
}

// Form validation before submit
document.getElementById('productForm').addEventListener('submit', function(e) {
    const hasVariants = document.getElementById('hasVariants').checked;
    
    if (hasVariants) {
        // Check if variants are properly configured
        const visibleVariants = document.querySelectorAll('.variant-section:not(.hidden)');
        let hasValidVariant = false;
        
        visibleVariants.forEach(section => {
            const nameInput = section.querySelector('input[name="variant_names[]"]');
            const options = section.querySelectorAll('input[name^="variant_options"]');
            
            if (nameInput.value.trim() && options.length > 0) {
                hasValidVariant = true;
            }
        });
        
        if (!hasValidVariant) {
            e.preventDefault();
            alert('Harap lengkapi minimal satu varian dengan nama dan opsi.');
            return;
        }
        
        // Check if variant combinations have required data
        const variantPriceInputs = document.querySelectorAll('input[name*="[price]"]');
        let hasEmptyPrice = false;
        
        variantPriceInputs.forEach(input => {
            if (!input.value.trim()) {
                hasEmptyPrice = true;
            }
        });
        
        if (hasEmptyPrice) {
            const confirmSubmit = confirm('Beberapa varian belum memiliki harga. Lanjutkan menyimpan?');
            if (!confirmSubmit) {
                e.preventDefault();
                return;
            }
        }
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';
});

// Handle variant name changes to regenerate combinations
document.addEventListener('input', function(e) {
    if (e.target.name === 'variant_names[]') {
        // Delay generation to avoid too many calls
        clearTimeout(window.variantNameTimeout);
        window.variantNameTimeout = setTimeout(() => {
            generateVariantCombinations();
        }, 500);
    }
});
</script>

<!-- Image Modal (will be created dynamically by JavaScript) -->

<style>
/* Additional custom styles for better UX */
.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.variant-section {
    transition: all 0.3s ease;
}

.option-display-container .flex {
    transition: all 0.2s ease;
}

.option-display-container .flex:hover {
    background-color: #E5E7EB;
}

/* Loading spinner for submit button */
.loading {
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Image preview hover effects */
.existing-image:hover, .relative.border-2:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

/* Modal styles */
.modal-overlay {
    backdrop-filter: blur(4px);
}

/* Table responsive styles */
@media (max-width: 768px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    .min-w-full {
        min-width: 800px;
    }
}

/* Alert animations */
#successAlert, #errorAlert {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Variant combination table improvements */
#variantCombinationsContainer table {
    font-size: 0.875rem;
}

#variantCombinationsContainer input {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

button:active {
    transform: translateY(0);
}

/* File input styling */
input[type="file"] {
    cursor: pointer;
}

input[type="file"]::-webkit-file-upload-button {
    background: #3B82F6;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
    margin-right: 0.5rem;
}

input[type="file"]::-webkit-file-upload-button:hover {
    background: #2563EB;
}

/* Option tags styling */
.option-display-container .flex {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.remove-option {
    font-size: 1.25rem;
    line-height: 1;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive grid improvements */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .md\:grid-cols-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>

@endsection
        