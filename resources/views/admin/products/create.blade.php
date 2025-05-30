@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<h1 class="text-xl font-bold mb-4">Tambah Produk</h1>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Bagian Utama</h2>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}" 
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
                    <option value="{{ $category->id }}" {{ old('category_id', $selectedCategoryId ?? '') == $category->id ? 'selected' : '' }}>
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
                      rows="4">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Foto Produk <span class="text-sm text-gray-500">(Maksimal 6 foto, rasio 1:1)</span>
            </label>
            
            <div class="mb-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <span id="uploadedCount">0</span> / 6 foto terupload
                </span>
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
                <p class="text-xs text-gray-500 mt-1">Pilih hingga 6 foto sekaligus. Format: JPG, PNG, GIF. Maksimal 2MB per foto.</p>
            </div>
            @error('images')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Hidden container untuk menyimpan file yang dipilih -->
            <div id="hiddenFilesContainer"></div>
            
            <div id="imagePreviewContainer" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                <!-- Preview images will be inserted here -->
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informasi Produk</h2>
        
        <div class="mb-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="has_variants" id="hasVariants" 
                       class="form-checkbox h-5 w-5 text-blue-600" value="1" 
                       {{ old('has_variants') ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Gunakan Varian</span>
            </label>
        </div>

        <!-- Bagian untuk produk TANPA varian -->
        <div id="simpleProductFields" class="{{ old('has_variants') ? 'hidden' : '' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
                           class="form-input w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                           class="form-input w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight') }}" min="0" step="0.01"
                           class="form-input w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('weight')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dimensi Paket (cm)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="number" name="length" placeholder="Panjang" value="{{ old('length') }}" min="0" step="0.1"
                               class="form-input border border-gray-300 rounded-md px-3 py-2">
                        <input type="number" name="width" placeholder="Lebar" value="{{ old('width') }}" min="0" step="0.1"
                               class="form-input border border-gray-300 rounded-md px-3 py-2">
                        <input type="number" name="height" placeholder="Tinggi" value="{{ old('height') }}" min="0" step="0.1"
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
        <div id="variantFields" class="{{ old('has_variants') ? '' : 'hidden' }}">
            <div id="variantContainer">
                <!-- Varian 1 -->
                <div class="mb-6 variant-section" data-variant-index="0">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Varian 1</label>
                    </div>
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="variant_names[]" placeholder="Nama varian (contoh: Motif)" 
                               class="form-input flex-1 border border-gray-300 rounded-md px-3 py-2" 
                               value="{{ old('variant_names.0') }}">
                    </div>
                    
                    <div class="options-container mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Varian</label>
                        <div class="flex flex-wrap gap-2 mb-2 option-display-container">
                            @if(old('variant_options.0'))
                                @foreach(old('variant_options.0') as $option)
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
                <div class="mb-6 variant-section hidden" data-variant-index="1">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Varian 2</label>
                        <button type="button" class="remove-variant-btn text-red-500 hover:text-red-700 text-sm">
                            Hapus Varian
                        </button>
                    </div>
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="variant_names[]" placeholder="Nama varian (contoh: Ukuran)" 
                               class="form-input flex-1 border border-gray-300 rounded-md px-3 py-2" 
                               value="{{ old('variant_names.1') }}">
                    </div>
                    
                    <div class="options-container mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Varian</label>
                        <div class="flex flex-wrap gap-2 mb-2 option-display-container">
                            @if(old('variant_options.1'))
                                @foreach(old('variant_options.1') as $option)
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
                <button type="button" id="addVariantBtn" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300 text-sm">
                    + Tambah Varian
                </button>
                <p class="text-xs text-gray-500 mt-1">Maksimal 2 varian</p>
            </div>

            <!-- Tabel Kombinasi Varian -->
            <div id="variantCombinationsContainer" class="hidden">
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
            Simpan Produk
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
let variantOptions = [[], []]; // Untuk menyimpan opsi varian
let fileCounter = 0;

// Initialize variant options from old input
@if(old('variant_options'))
    @foreach(old('variant_options') as $index => $options)
        @if($options)
            variantOptions[{{ $index }}] = @json($options);
        @endif
    @endforeach
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
});

// Initialize form based on existing data
function initializeForm() {
    const hasVariants = document.getElementById('hasVariants').checked;
    if (hasVariants) {
        generateVariantCombinations();
    }
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

// Generate table body - PERBAIKAN
function generateTableBody(combinations) {
    const tbody = document.getElementById('variantCombinationsBody');
    tbody.innerHTML = '';
    
    combinations.forEach((combination, index) => {
        const tr = document.createElement('tr');
        tr.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
        
        // Kolom foto
        const tdImage = document.createElement('td');
        tdImage.className = 'px-4 py-2 border-b';
        tdImage.innerHTML = `
            <input type="file" name="variant_images[]" 
                   class="variant-image-input text-xs border border-gray-300 rounded px-2 py-1"
                   accept="image/jpeg,image/png,image/gif">
        `;
        tr.appendChild(tdImage);
        
        // Kolom opsi varian + hidden inputs untuk combinations
        combination.forEach((option, optIndex) => {
            const td = document.createElement('td');
            td.className = 'px-4 py-2 border-b';
            td.innerHTML = `
                <span class="font-medium">${option}</span>
                <input type="hidden" name="variant_combinations[${index}][${optIndex}]" value="${option}">
            `;
            tr.appendChild(td);
        });
        
        // Kolom data produk
        const fields = [
            { name: 'variant_prices', type: 'number', min: '0', step: '0.01', placeholder: '0', required: true },
            { name: 'variant_stocks', type: 'number', min: '0', value: '0' },
            { name: 'variant_weights', type: 'number', min: '0', step: '0.01', placeholder: '0' },
            { name: 'variant_lengths', type: 'number', min: '0', step: '0.1', placeholder: '0' },
            { name: 'variant_widths', type: 'number', min: '0', step: '0.1', placeholder: '0' },
            { name: 'variant_heights', type: 'number', min: '0', step: '0.1', placeholder: '0' }
        ];
        
        fields.forEach(field => {
            const td = document.createElement('td');
            td.className = 'px-4 py-2 border-b';
            
            let inputHtml = `
                <input type="${field.type}" name="${field.name}[]" 
                       ${field.value ? `value="${field.value}"` : ''}
                       ${field.placeholder ? `placeholder="${field.placeholder}"` : ''}
                       ${field.min ? `min="${field.min}"` : ''}
                       ${field.step ? `step="${field.step}"` : ''}
                       ${field.required ? 'required' : ''}
                       class="form-input w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
            `;
            
            td.innerHTML = inputHtml;
            tr.appendChild(td);
        });
        
        tbody.appendChild(tr);
    });
    
    console.log(`Generated ${combinations.length} variant combinations`);
}

// Image handling dengan perbaikan
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    if (uploadedImages.length + files.length > maxImages) {
        alert(`Maksimal ${maxImages} foto. Anda sudah mengupload ${uploadedImages.length} foto.`);
        e.target.value = '';
        return;
    }

    const validFiles = [];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    for (const file of files) {
        if (!allowedTypes.includes(file.type)) {
            alert(`File ${file.name} bukan format gambar yang valid. Gunakan JPG, PNG, atau GIF.`);
            continue;
        }
        
        if (file.size > maxFileSize) {
            alert(`File ${file.name} terlalu besar. Maksimal 2MB per foto.`);
            continue;
        }
        
        validFiles.push(file);
    }

    validFiles.forEach(file => {
        if (uploadedImages.length < maxImages) {
            uploadedImages.push(file);
        }
    });

    updateImagePreview();
    updateUploadCounter();
    updateHiddenFileInputs();
    e.target.value = '';
});

// Update hidden file inputs - PERBAIKAN
function updateHiddenFileInputs() {
    const container = document.getElementById('hiddenFilesContainer');
    const originalInput = document.getElementById('imageUpload');
    
    // Clear existing hidden inputs
    container.innerHTML = '';
    
    // Create new DataTransfer to hold all files
    const dt = new DataTransfer();
    
    // Add all uploaded images to DataTransfer
    uploadedImages.forEach(file => {
        dt.items.add(file);
    });
    
    // Update the original input's files
    originalInput.files = dt.files;
}

// Update image preview
function updateImagePreview() {
    const container = document.getElementById('imagePreviewContainer');
    container.innerHTML = '';

    uploadedImages.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 hover:bg-gray-100 transition-colors';
            previewDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}" 
                     class="w-full h-32 object-cover rounded cursor-pointer" 
                     onclick="openImageModal('${e.target.result}')">
                <button type="button" onclick="removeImage(${index})" 
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 transition-colors"
                        title="Hapus foto">
                    ×
                </button>
                <p class="text-xs text-gray-500 mt-1 text-center truncate" title="${file.name}">${file.name}</p>
            `;
            container.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    });
}

// Remove image
function removeImage(index) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        uploadedImages.splice(index, 1);
        updateImagePreview();
        updateUploadCounter();
        updateHiddenFileInputs();
    }
}

// Update upload counter
function updateUploadCounter() {
    document.getElementById('uploadedCount').textContent = uploadedImages.length;
}

// Open image modal
function openImageModal(src) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-full p-4">
            <img src="${src}" class="max-w-full max-h-full object-contain rounded-lg">
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="absolute top-2 right-2 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-200 transition-colors shadow-lg">
                ×
            </button>
        </div>
    `;
    modal.onclick = (e) => {
        if (e.target === modal) modal.remove();
    };
    document.body.appendChild(modal);
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
    
    // Restore body scroll when modal is closed
    modal.addEventListener('remove', () => {
        document.body.style.overflow = 'auto';
    });
}

// Perbaikan form validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const hasVariants = document.getElementById('hasVariants').checked;
    
    console.log('Form submission started');
    console.log('Has variants:', hasVariants);
    console.log('Uploaded images:', uploadedImages.length);
    
    // Disable submit button to prevent double submission
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';
    
    // Validasi upload gambar
    if (uploadedImages.length === 0) {
        e.preventDefault();
        alert('Anda harus mengupload minimal 1 foto produk.');
        resetSubmitButton();
        return false;
    }
    
    // Update hidden file inputs before submission
    updateHiddenFileInputs();
    
    // Validasi nama produk
    const productName = document.querySelector('input[name="name"]').value.trim();
    if (!productName) {
        e.preventDefault();
        alert('Nama produk harus diisi.');
        resetSubmitButton();
        return false;
    }
    
    // Validasi kategori
    const categoryId = document.querySelector('select[name="category_id"]').value;
    if (!categoryId) {
        e.preventDefault();
        alert('Pilih kategori produk.');
        resetSubmitButton();
        return false;
    }
    
    if (hasVariants) {
        // Validasi untuk produk dengan varian
        const visibleVariants = document.querySelectorAll('.variant-section:not(.hidden)');
        
        // Cek apakah ada minimal 1 varian yang diisi
        let hasValidVariant = false;
        let variantNames = [];
        
        visibleVariants.forEach(section => {
            const variantIndex = parseInt(section.dataset.variantIndex);
            const variantName = section.querySelector('input[name="variant_names[]"]').value.trim();
            
            if (variantName && variantOptions[variantIndex].length > 0) {
                hasValidVariant = true;
                variantNames.push(variantName);
            }
        });
        
        if (!hasValidVariant) {
            e.preventDefault();
            alert('Anda harus mengisi minimal 1 varian dengan nama dan opsi.');
            resetSubmitButton();
            return false;
        }
        
        // Validasi kombinasi varian
        const variantPrices = document.querySelectorAll('input[name="variant_prices[]"]');
        let hasValidPrice = false;
        let validPriceCount = 0;
        
        variantPrices.forEach(priceInput => {
            const price = parseFloat(priceInput.value);
            if (!isNaN(price) && price > 0) {
                hasValidPrice = true;
                validPriceCount++;
            }
        });
        
        if (!hasValidPrice) {
            e.preventDefault();
            alert('Anda harus mengisi minimal 1 harga varian yang valid (lebih dari 0).');
            resetSubmitButton();
            return false;
        }
        
        console.log('Valid variant prices:', validPriceCount);
        
        // Pastikan ada input untuk variant_combinations
        const combinations = document.querySelectorAll('input[name^="variant_combinations"]');
        if (combinations.length === 0) {
            console.log('No variant combinations found, regenerating...');
            generateVariantCombinations();
        }
        
    } else {
        // Validasi untuk produk tanpa varian
        const price = document.querySelector('input[name="price"]').value;
        if (!price || parseFloat(price) <= 0) {
            e.preventDefault();
            alert('Harga produk harus diisi dan lebih dari 0.');
            resetSubmitButton();
            return false;
        }
        
        const stock = document.querySelector('input[name="stock"]').value;
        if (stock === '' || parseInt(stock) < 0) {
            e.preventDefault();
            alert('Stok produk harus diisi dengan nilai minimal 0.');
            resetSubmitButton();
            return false;
        }
    }
    
    console.log('Form validation passed, submitting...');
    
    // Show loading overlay
    showLoading();
    
    // Jika semua validasi lolos, biarkan form submit
    return true;
});

// Reset submit button
function resetSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Simpan Produk';
}

// Prevent form submission on Enter key in input fields (except textarea)
document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.type !== 'submit') {
        // Allow Enter in specific cases
        if (e.target.classList.contains('new-option-input')) {
            return; // Already handled above
        }
        e.preventDefault();
    }
});

// Auto-resize textarea
document.addEventListener('input', function(e) {
    if (e.target.tagName === 'TEXTAREA') {
        e.target.style.height = 'auto';
        e.target.style.height = e.target.scrollHeight + 'px';
    }
});

// Format number inputs
document.addEventListener('input', function(e) {
    if (e.target.type === 'number') {
        const value = parseFloat(e.target.value);
        if (e.target.hasAttribute('min') && value < parseFloat(e.target.min)) {
            e.target.value = e.target.min;
        }
    }
});

// Drag and drop functionality for images
const imageUploadArea = document.getElementById('imageUpload').parentElement;

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    imageUploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    imageUploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    imageUploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    imageUploadArea.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight(e) {
    imageUploadArea.classList.remove('border-blue-500', 'bg-blue-50');
}

imageUploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    const imageUpload = document.getElementById('imageUpload');
    imageUpload.files = files;
    
    // Trigger change event
    const event = new Event('change', { bubbles: true });
    imageUpload.dispatchEvent(event);
}

// Add loading state to form - PERBAIKAN
function showLoading() {
    // Remove existing overlay if any
    const existingOverlay = document.getElementById('loadingOverlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    const overlay = document.createElement('div');
    overlay.id = 'loadingOverlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3 shadow-lg">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
            <span class="text-gray-700">Menyimpan produk...</span>
        </div>
    `;
    document.body.appendChild(overlay);
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Initialize tooltips for help text
document.querySelectorAll('[title]').forEach(element => {
    element.addEventListener('mouseenter', function() {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute bg-black text-white text-xs rounded py-1 px-2 z-50';
        tooltip.textContent = this.title;
        tooltip.style.top = this.offsetTop - 30 + 'px';
        tooltip.style.left = this.offsetLeft + 'px';
        this.parentElement.appendChild(tooltip);
        this.removeAttribute('title');
        this.setAttribute('data-original-title', tooltip.textContent);
    });
    
    element.addEventListener('mouseleave', function() {
        const tooltip = this.parentElement.querySelector('.absolute.bg-black');
        if (tooltip) tooltip.remove();
        if (this.getAttribute('data-original-title')) {
            this.title = this.getAttribute('data-original-title');
            this.removeAttribute('data-original-title');
        }
    });
});

// Tambahan: Debug function untuk melihat data form sebelum submit
function debugFormData() {
    const formData = new FormData(document.getElementById('productForm'));
    
    console.log('=== FORM DEBUG ===');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    console.log('=== END DEBUG ===');
}

// Panggil debug saat submit (untuk development)
document.getElementById('productForm').addEventListener('submit', function(e) {
    setTimeout(() => {
        debugFormData();
    }, 100);
});

// Perbaikan error handling
window.addEventListener('beforeunload', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn.disabled && submitBtn.textContent === 'Menyimpan...') {
        // Reset button jika user mencoba refresh/close saat loading
        resetSubmitButton();
        
        // Remove loading overlay
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.remove();
        document.body.style.overflow = 'auto';
    }
});
</script>

<style>
    .form-input, .form-select, .form-textarea {
        @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
    }
    
    .form-input:invalid, .form-select:invalid, .form-textarea:invalid {
        @apply border-red-300 focus:ring-red-500 focus:border-red-500;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    .variant-image-input {
        @apply w-full text-sm text-gray-500;
    }
</style>
@endsection