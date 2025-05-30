@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Daftar Produk</h1>

        @if(isset($category))
            <div class="mb-4 p-3 bg-blue-50 border-l-4 border-blue-400 text-blue-700">
                Menampilkan produk untuk kategori: <strong>{{ $category->name }}</strong>
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('admin.products.create', request()->only('category_id')) }}" 
               class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Produk
            </a>
        </div>

        <!-- Responsive Table Container -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            @php
                                // Helper function untuk handle images yang bisa berupa string atau array
                                $productImages = [];
                                if ($product->images) {
                                    if (is_array($product->images)) {
                                        $productImages = $product->images;
                                    } elseif (is_string($product->images)) {
                                        // Jika string, coba decode JSON atau buat array dengan single value
                                        $decoded = json_decode($product->images, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $productImages = $decoded;
                                        } else {
                                            // Jika bukan JSON valid, treat sebagai single image path
                                            $productImages = [$product->images];
                                        }
                                    }
                                }
                                $imageCount = count($productImages);
                            @endphp
                            
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $product->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    @if($product->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->stock }} pcs
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($imageCount > 0)
                                        <div class="flex items-center space-x-2">
                                            <!-- Main Image -->
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $productImages[0]) }}" 
                                                     alt="Gambar Produk" 
                                                     class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-blue-400 transition-colors"
                                                     onclick="openImageModal('{{ $product->id }}')">
                                                
                                                <!-- Image Counter Badge -->
                                                @if($imageCount > 1)
                                                    <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                                        {{ $imageCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Image Count Text -->
                                            <div class="text-xs text-gray-500">
                                                {{ $imageCount }} foto
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.products.show', $product->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                        
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                              method="POST" 
                                              style="display:inline;"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada produk ditemukan</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan produk pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination if available -->
        @if(method_exists($products, 'links'))
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold">Foto Produk</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-4">
                <!-- Images will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Process products data untuk modal dengan handling images yang proper
        const productsData = {};
        @foreach($products as $product)
            @php
                $productImages = [];
                if ($product->images) {
                    if (is_array($product->images)) {
                        $productImages = $product->images;
                    } elseif (is_string($product->images)) {
                        $decoded = json_decode($product->images, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $productImages = $decoded;
                        } else {
                            $productImages = [$product->images];
                        }
                    }
                }
            @endphp
            productsData[{{ $product->id }}] = {
                id: {{ $product->id }},
                name: {!! json_encode($product->name) !!},
                images: {!! json_encode($productImages) !!}
            };
        @endforeach

        function openImageModal(productId) {
            const product = productsData[productId];
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('modalContent');
            
            if (product && product.images && product.images.length > 0) {
                let imagesHtml = `
                    <h4 class="font-medium mb-4">${product.name} - ${product.images.length} Foto</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                `;
                
                product.images.forEach((image, index) => {
                    imagesHtml += `
                        <div class="relative">
                            <img src="{{ asset('storage/') }}/${image}" 
                                 alt="Foto ${index + 1}" 
                                 class="w-full h-64 object-cover rounded-lg border">
                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-sm">
                                Foto ${index + 1}
                            </div>
                        </div>
                    `;
                });
                
                imagesHtml += '</div>';
                modalContent.innerHTML = imagesHtml;
                modal.classList.remove('hidden');
            }
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
@endsection