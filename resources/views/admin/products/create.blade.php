@extends('layouts.admin')

@section('content')
<!-- Ensure CSRF token is available -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<h1 class="text-xl font-bold mb-4">Tambah Produk</h1>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

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
        <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
        <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
               class="form-input w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('price')
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

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Foto Produk 
            <span class="text-sm text-gray-500">(Maksimal 6 foto)</span>
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

        <div id="imagePreviewContainer" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
            <!-- Preview images will be inserted here -->
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Size</label>
        <select name="size_type" id="sizeType" class="form-select w-full border border-gray-300 rounded-md px-3 py-2" required>
            <option value="">Pilih Jenis Size</option>
            <option value="letter" {{ old('size_type') == 'letter' ? 'selected' : '' }}>Huruf (XS - XXL)</option>
            <option value="number" {{ old('size_type') == 'number' ? 'selected' : '' }}>Angka (35 - 50)</option>
        </select>
        @error('size_type')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div id="sizeOptions" class="mb-4 hidden">
        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Size dan Input Stok</label>
        <div id="sizesContainer" class="grid grid-cols-2 gap-2 mt-2">
            <!-- Will be filled with JavaScript -->
        </div>
        @error('sizes')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        @error('stocks')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Total Stok</label>
        <input type="number" name="total_stock_display" id="totalStock" 
               class="form-input w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2" readonly>
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
    const letterSizes = @json($letterSizes ?? []);
    const numberSizes = @json($numberSizes ?? []);
    const maxImages = 6;
    const maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
    let uploadedImages = [];

    // Initialize form on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Restore size type if coming back from validation error
        const sizeType = document.getElementById('sizeType').value;
        if (sizeType) {
            handleSizeTypeChange(sizeType);
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('#successAlert, #errorAlert');
            alerts.forEach(alert => {
                if (alert) alert.style.display = 'none';
            });
        }, 5000);
    });

    // Size handling
    document.getElementById('sizeType').addEventListener('change', function() {
        handleSizeTypeChange(this.value);
    });

    function handleSizeTypeChange(type) {
        const sizes = type === 'letter' ? letterSizes : numberSizes;
        const container = document.getElementById('sizesContainer');
        const sizeOptions = document.getElementById('sizeOptions');
        
        container.innerHTML = '';
        document.getElementById('totalStock').value = 0;

        if (type && sizes.length > 0) {
            sizeOptions.classList.remove('hidden');
            sizes.forEach(size => {
                const div = document.createElement('div');
                div.className = 'mb-2';
                div.innerHTML = `
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="sizes[]" value="${size.id}" 
                               onchange="toggleStockInput(this)" class="size-checkbox">
                        <span class="text-sm">${size.name}</span>
                    </label>
                    <input type="number" name="stocks[${size.id}]" data-size="${size.id}" 
                           class="form-input w-full mt-1 stock-input hidden border border-gray-300 rounded-md px-3 py-2" 
                           placeholder="Jumlah stok untuk ${size.name}" oninput="updateTotalStock()" min="0" value="0">
                `;
                container.appendChild(div);
            });
        } else {
            sizeOptions.classList.add('hidden');
        }
    }

    function toggleStockInput(checkbox) {
        const stockInput = checkbox.parentElement.nextElementSibling;
        if (checkbox.checked) {
            stockInput.classList.remove('hidden');
            stockInput.required = true;
        } else {
            stockInput.classList.add('hidden');
            stockInput.required = false;
            stockInput.value = 0;
        }
        updateTotalStock();
    }

    function updateTotalStock() {
        let total = 0;
        document.querySelectorAll('.stock-input:not(.hidden)').forEach(input => {
            const value = parseInt(input.value) || 0;
            total += value;
        });
        document.getElementById('totalStock').value = total;
    }

    // Image handling with validation
    document.getElementById('imageUpload').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Validate file count
        if (uploadedImages.length + files.length > maxImages) {
            showAlert(`Maksimal ${maxImages} foto. Anda sudah mengupload ${uploadedImages.length} foto.`, 'error');
            e.target.value = '';
            return;
        }

        // Validate each file
        const validFiles = [];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        for (const file of files) {
            // Check file type
            if (!allowedTypes.includes(file.type)) {
                showAlert(`File ${file.name} bukan format gambar yang valid. Gunakan JPG, PNG, atau GIF.`, 'error');
                continue;
            }
            
            // Check file size
            if (file.size > maxFileSize) {
                showAlert(`File ${file.name} terlalu besar. Maksimal 2MB per foto.`, 'error');
                continue;
            }
            
            validFiles.push(file);
        }

        // Add valid files
        validFiles.forEach(file => {
            if (uploadedImages.length < maxImages) {
                uploadedImages.push(file);
            }
        });

        updateImagePreview();
        updateUploadCounter();
        e.target.value = ''; // Reset input
    });

    function updateImagePreview() {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';

        uploadedImages.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50';
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" 
                         class="w-full h-32 object-cover rounded cursor-pointer" 
                         onclick="openImageModal('${e.target.result}')">
                    <button type="button" onclick="removeImage(${index})" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 transition-colors"
                            title="Hapus foto">
                        ×
                    </button>
                    <p class="text-xs text-gray-500 mt-1 text-center truncate">${file.name}</p>
                `;
                container.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        uploadedImages.splice(index, 1);
        updateImagePreview();
        updateUploadCounter();
    }

    function updateUploadCounter() {
        document.getElementById('uploadedCount').textContent = uploadedImages.length;
    }

    function openImageModal(src) {
        // Simple image preview modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="relative max-w-4xl max-h-full p-4">
                <img src="${src}" class="max-w-full max-h-full object-contain">
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="absolute top-2 right-2 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-200">
                    ×
                </button>
            </div>
        `;
        modal.onclick = (e) => {
            if (e.target === modal) modal.remove();
        };
        document.body.appendChild(modal);
    }

    // Form submission - Use traditional form submission instead of AJAX
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        if (!validateForm()) {
            return false;
        }

        // Create a new form element
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.action;
        form.enctype = 'multipart/form-data';
        form.style.display = 'none';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('input[name="_token"]').value;
        form.appendChild(csrfInput);

        // Get all form data except images
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            if (key !== 'images[]') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        }

        // Handle stock data correctly - only send data for checked sizes
        const checkedSizes = document.querySelectorAll('.size-checkbox:checked');
        const sizeIds = [];
        const stockValues = [];
        
        checkedSizes.forEach(checkbox => {
            const sizeId = checkbox.value;
            const stockInput = document.querySelector(`input[name="stocks[${sizeId}]"]`);
            const stockValue = stockInput ? parseInt(stockInput.value) || 0 : 0;
            
            if (stockValue > 0) {
                sizeIds.push(sizeId);
                stockValues.push(stockValue);
            }
        });

        // Clear existing size/stock inputs and add new ones
        form.querySelectorAll('input[name="sizes[]"], input[name^="stocks"]').forEach(input => {
            input.remove();
        });

        // Add size IDs
        sizeIds.forEach(sizeId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sizes[]';
            input.value = sizeId;
            form.appendChild(input);
        });

        // Add stock values with corresponding indices
        stockValues.forEach((stockValue, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'stocks[]';
            input.value = stockValue;
            form.appendChild(input);
        });

        // Add images if any
        if (uploadedImages.length > 0) {
            const imageInput = document.createElement('input');
            imageInput.type = 'file';
            imageInput.name = 'images[]';
            imageInput.multiple = true;
            imageInput.style.display = 'none';
            
            // Create DataTransfer to add files
            const dt = new DataTransfer();
            uploadedImages.forEach(file => {
                dt.items.add(file);
            });
            imageInput.files = dt.files;
            form.appendChild(imageInput);
        }

        // Disable submit button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Menyimpan...';

        // Add form to body and submit
        document.body.appendChild(form);
        form.submit();
    });

    function validateForm() {
        const checkedSizes = document.querySelectorAll('.size-checkbox:checked');
        const hasStockInputs = document.querySelectorAll('.stock-input:not(.hidden)');
        
        // Check if at least one size is selected
        if (checkedSizes.length === 0) {
            showAlert('Pilih minimal satu ukuran produk.', 'error');
            return false;
        }

        // Check if all selected sizes have stock > 0
        let hasValidStock = false;
        hasStockInputs.forEach(input => {
            const stock = parseInt(input.value) || 0;
            if (stock > 0) {
                hasValidStock = true;
            }
        });

        if (!hasValidStock) {
            showAlert('Minimal satu ukuran harus memiliki stok lebih dari 0.', 'error');
            return false;
        }

        return true;
    }

    function showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 px-4 py-3 rounded z-50 custom-alert transition-all duration-300 max-w-md ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
            type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
            'bg-blue-100 border border-blue-400 text-blue-700'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1 pr-2">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="flex-shrink-0 text-lg leading-none hover:opacity-70" 
                        title="Tutup">&times;</button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 8 seconds for errors, 5 seconds for others
        const timeout = type === 'error' ? 8000 : 5000;
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, timeout);
    }
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
</style>
@endsection