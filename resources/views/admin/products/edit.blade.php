@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900">Edit Produk</h1>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi produk dengan detail</p>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-8">
                    
                    <!-- Upload Foto Produk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Foto Produk <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(1-6 foto, max 2MB per foto)</span>
                        </label>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            @for($i = 0; $i < 6; $i++)
                            <div class="relative">
                                <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer image-upload-box" 
                                     onclick="document.getElementById('image_{{ $i }}').click()">
                                    @if(isset($product->images[$i]))
                                    <img src="{{ asset('storage/' . $product->images[$i]->image_path) }}" 
                                         class="w-full h-full object-cover rounded-lg image-preview" alt="Current Image">
                                    <div class="text-center image-placeholder hidden">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <p class="text-xs text-gray-500 mt-1">Tambah Foto</p>
                                    </div>
                                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 remove-image">×</button>
                                    @else
                                    <div class="text-center image-placeholder">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <p class="text-xs text-gray-500 mt-1">Tambah Foto</p>
                                    </div>
                                    <img class="w-full h-full object-cover rounded-lg image-preview hidden" alt="Preview">
                                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 remove-image hidden">×</button>
                                    @endif
                                </div>
                                <input type="file" id="image_{{ $i }}" name="images[]" accept="image/*" class="hidden image-input">
                            </div>
                            @endfor
                        </div>
                        @error('images')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Masukkan nama produk" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Produk <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Deskripsikan produk Anda..." required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Pilih Kategori</option>
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
                    </div>

                    <!-- Toggle Varian -->
                    <div class="border-t pt-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="use_variant" id="use_variant" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('use_variant', $product->use_variant) ? 'checked' : '' }}>
                            <label for="use_variant" class="ml-2 block text-sm font-medium text-gray-700">
                                Gunakan Varian Produk
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Aktifkan jika produk memiliki pilihan seperti warna, ukuran, dll.</p>
                    </div>

                    <!-- Informasi Produk Tanpa Varian -->
                    <div id="product-info-no-variant" {{ $product->use_variant ? 'class=hidden' : '' }}>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Produk</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                                <input type="number" name="price" id="price" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0" value="{{ old('price', $product->price) }}">
                            </div>
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                                <input type="number" name="stock" id="stock" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0" value="{{ old('stock', $product->stock) }}">
                            </div>
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Berat (gram)</label>
                                <input type="number" name="weight" id="weight" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0" value="{{ old('weight', $product->weight) }}">
                            </div>
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700 mb-2">Panjang (cm)</label>
                                <input type="number" name="length" id="length" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0" value="{{ old('length', $product->length) }}">
                            </div>
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700 mb-2">Lebar (cm)</label>
                                <input type="number" name="width" id="width" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0" value="{{ old('width', $product->width) }}">
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Tinggi (cm)</label>
                                <input type="number" name="height" id="height" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0" value="{{ old('height', $product->height) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Pengaturan Varian -->
                    <div id="variant-settings" {{ !$product->use_variant ? 'class=hidden' : '' }}>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Varian</h3>
                        
                        <!-- Varian 1 -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <h4 class="font-medium text-gray-900 mb-3">Varian 1 <span class="text-red-500">*</span></h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Varian</label>
                                    <input type="text" name="variant_name_1" id="variant_name_1" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Contoh: Warna" value="{{ old('variant_name_1', $product->variant_name_1) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Varian</label>
                                    <input type="text" id="variant_options_1" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Contoh: Putih, Merah, Biru (pisahkan dengan koma)"
                                           value="{{ $product->variants->pluck('variant_option_1')->unique()->implode(', ') }}">
                                    <div id="variant_1_tags" class="flex flex-wrap gap-2 mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Varian 2 -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">Varian 2 (Opsional)</h4>
                                <button type="button" id="toggle-variant-2" class="text-blue-600 text-sm hover:text-blue-800">
                                    {{ $product->variant_name_2 ? '- Hapus Varian Kedua' : '+ Tambah Varian Kedua' }}
                                </button>
                            </div>
                            <div id="variant-2-fields" {{ !$product->variant_name_2 ? 'class=hidden' : '' }}>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Varian</label>
                                        <input type="text" name="variant_name_2" id="variant_name_2" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Contoh: Ukuran" value="{{ old('variant_name_2', $product->variant_name_2) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Varian</label>
                                        <input type="text" id="variant_options_2" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Contoh: S, M, L, XL (pisahkan dengan koma)"
                                               value="{{ $product->variants->pluck('variant_option_2')->filter()->unique()->implode(', ') }}">
                                        <div id="variant_2_tags" class="flex flex-wrap gap-2 mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Kombinasi Varian -->
                        <div id="variant-combinations" {{ $product->variants->count() > 0 ? '' : 'class=hidden' }}>
                            <h4 class="font-medium text-gray-900 mb-4">Kombinasi Varian</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr id="variant-table-header">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="variant-1-header">{{ $product->variant_name_1 ?: 'Varian 1' }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ !$product->variant_name_2 ? 'hidden' : '' }}" id="variant-2-header">{{ $product->variant_name_2 ?: 'Varian 2' }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P×L×T</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variant-table-body" class="bg-white divide-y divide-gray-200">
                                        @foreach($product->variants as $index => $variant)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50 hover:bg-gray-100 cursor-pointer variant-image-upload" onclick="document.getElementById('variant_image_{{ $index }}').click()">
                                                        @if($variant->variant_image)
                                                        <img src="{{ asset('storage/' . $variant->variant_image) }}" 
                                                             class="w-full h-full object-cover rounded-lg variant-preview" alt="Variant Image">
                                                        <svg class="w-6 h-6 text-gray-400 variant-placeholder hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                        @else
                                                        <svg class="w-6 h-6 text-gray-400 variant-placeholder" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                        <img class="w-full h-full object-cover rounded-lg variant-preview hidden" alt="Preview">
                                                        @endif
                                                    </div>
                                                    <input type="file" id="variant_image_{{ $index }}" name="variants[{{ $index }}][variant_image]" accept="image/*" class="hidden variant-image-input">
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="hidden" name="variants[{{ $index }}][variant_option_1]" value="{{ $variant->variant_option_1 }}">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $variant->variant_option_1 }}</span>
                                            </td>
                                            <td class="px-4 py-3 {{ !$variant->variant_option_2 ? 'hidden' : '' }}">
                                                @if($variant->variant_option_2)
                                                <input type="hidden" name="variants[{{ $index }}][variant_option_2]" value="{{ $variant->variant_option_2 }}">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $variant->variant_option_2 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" name="variants[{{ $index }}][price]" class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" value="{{ $variant->price }}" required>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" name="variants[{{ $index }}][stock]" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" value="{{ $variant->stock }}" required>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" name="variants[{{ $index }}][weight]" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" value="{{ $variant->weight }}" required>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex space-x-1">
                                                    <input type="number" name="variants[{{ $index }}][length]" class="w-12 px-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" value="{{ $variant->length }}" required>
                                                    <input type="number" name="variants[{{ $index }}][width]" class="w-12 px-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" value="{{ $variant->width }}" required>
                                                    <input type="number" name="variants[{{ $index }}][height]" class="w-12 px-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" value="{{ $variant->height }}" required>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const useVariantCheckbox = document.getElementById('use_variant');
        const productInfoNoVariant = document.getElementById('product-info-no-variant');
        const variantSettings = document.getElementById('variant-settings');
        const toggleVariant2 = document.getElementById('toggle-variant-2');
        const variant2Fields = document.getElementById('variant-2-fields');
        const variantCombinations = document.getElementById('variant-combinations');
        
        let variant1Options = [];
        let variant2Options = [];
    
        // Toggle variant functionality
        useVariantCheckbox.addEventListener('change', function() {
            if (this.checked) {
                productInfoNoVariant.classList.add('hidden');
                variantSettings.classList.remove('hidden');
            } else {
                productInfoNoVariant.classList.remove('hidden');
                variantSettings.classList.add('hidden');
                variantCombinations.classList.add('hidden');
            }
        });
    
        // Toggle variant 2
        toggleVariant2.addEventListener('click', function() {
            if (variant2Fields.classList.contains('hidden')) {
                variant2Fields.classList.remove('hidden');
                this.textContent = '- Hapus Varian Kedua';
                this.classList.add('text-red-600');
                this.classList.remove('text-blue-600');
            } else {
                variant2Fields.classList.add('hidden');
                this.textContent = '+ Tambah Varian Kedua';
                this.classList.add('text-blue-600');
                this.classList.remove('text-red-600');
                document.getElementById('variant_name_2').value = '';
                document.getElementById('variant_options_2').value = '';
                document.getElementById('variant_2_tags').innerHTML = '';
                variant2Options = [];
                generateVariantTable();
            }
        });
    
        // Handle variant options input
        document.getElementById('variant_options_1').addEventListener('input', function() {
            handleVariantOptions(this.value, 1);
        });
    
        document.getElementById('variant_options_2').addEventListener('input', function() {
            handleVariantOptions(this.value, 2);
        });
    
        function handleVariantOptions(value, variantNumber) {
            const options = value.split(',').map(opt => opt.trim()).filter(opt => opt !== '');
            const tagsContainer = document.getElementById(`variant_${variantNumber}_tags`);
            
            // Clear existing tags
            tagsContainer.innerHTML = '';
            
            // Create tags
            options.forEach(option => {
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                tag.textContent = option;
                tagsContainer.appendChild(tag);
            });
    
            // Update options array
            if (variantNumber === 1) {
                variant1Options = options;
            } else {
                variant2Options = options;
            }
    
            // Generate variant table if we have options
            if (variant1Options.length > 0) {
                generateVariantTable();
            }
        }
    
        function generateVariantTable() {
            if (variant1Options.length === 0) return;
    
            const tableBody = document.getElementById('variant-table-body');
            const variant1Header = document.getElementById('variant-1-header');
            const variant2Header = document.getElementById('variant-2-header');
            
            // Update headers
            variant1Header.textContent = document.getElementById('variant_name_1').value || 'Varian 1';
            
            if (variant2Options.length > 0) {
                variant2Header.textContent = document.getElementById('variant_name_2').value || 'Varian 2';
                variant2Header.classList.remove('hidden');
            } else {
                variant2Header.classList.add('hidden');
            }
    
            // Clear existing rows
            tableBody.innerHTML = '';
    
            // Generate combinations
            const combinations = [];
            if (variant2Options.length > 0) {
                // Two variants
                variant1Options.forEach(opt1 => {
                    variant2Options.forEach(opt2 => {
                        combinations.push([opt1, opt2]);
                    });
                });
            } else {
                // One variant
                variant1Options.forEach(opt1 => {
                    combinations.push([opt1]);
                });
            }
    
            // Create table rows
            combinations.forEach((combination, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                
                const isSecondVariant = combination.length === 2;
                
                row.innerHTML = `
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50 hover:bg-gray-100 cursor-pointer variant-image-upload" onclick="document.getElementById('variant_image_${index}').click()">
                                <svg class="w-6 h-6 text-gray-400 variant-placeholder" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <img class="w-full h-full object-cover rounded-lg variant-preview hidden" alt="Preview">
                            </div>
                            <input type="file" id="variant_image_${index}" name="variants[${index}][variant_image]" accept="image/*" class="hidden variant-image-input">
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <input type="hidden" name="variants[${index}][variant_option_1]" value="${combination[0]}">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${combination[0]}</span>
                    </td>
                    ${isSecondVariant ? `
                    <td class="px-4 py-3">
                        <input type="hidden" name="variants[${index}][variant_option_2]" value="${combination[1]}">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${combination[1]}</span>
                    </td>
                    ` : '<td class="px-4 py-3 hidden"></td>'}
                    <td class="px-4 py-3">
                        <input type="number" name="variants[${index}][price]" class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="0" required>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="variants[${index}][stock]" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="0" required>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="variants[${index}][weight]" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="0" required>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-1">
                            <input type="number" name="variants[${index}][length]" class="w-12 px-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="P" required>
                            <input type="number" name="variants[${index}][width]" class="w-12 px-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="L" required>
                            <input type="number" name="variants[${index}][height]" class="w-12 px-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="T" required>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
    
            // Show table
            variantCombinations.classList.remove('hidden');
    
            // Setup variant image uploads
            setupVariantImageUploads();
        }
    
        function setupVariantImageUploads() {
            document.querySelectorAll('.variant-image-input').forEach(input => {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    const container = this.parentElement;
                    const preview = container.querySelector('.variant-preview');
                    const placeholder = container.querySelector('.variant-placeholder');
                    
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                });
            });
        }
    
        // Main product image uploads
        document.querySelectorAll('.image-input').forEach((input, index) => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const container = this.parentElement.querySelector('.image-upload-box');
                const preview = container.querySelector('.image-preview');
                const placeholder = container.querySelector('.image-placeholder');
                const removeBtn = container.querySelector('.remove-image');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        removeBtn.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    
        // Remove image functionality
        document.querySelectorAll('.remove-image').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const container = this.parentElement;
                const preview = container.querySelector('.image-preview');
                const placeholder = container.querySelector('.image-placeholder');
                const input = container.parentElement.querySelector('.image-input');
                
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                this.classList.add('hidden');
                input.value = '';
            });
        });
    });
    </script>
@endsection
    