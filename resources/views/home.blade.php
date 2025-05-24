@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
     <!-- Banner Slider - Focused on Indonesian Local Fashion -->
     <div class="relative rounded-xl overflow-hidden mb-8 md:mb-12 shadow-lg">
        <div class="swiper bannerSwiper h-[200px] md:h-[400px] lg:h-[500px] w-full">
            <div class="swiper-wrapper">
                <!-- Slide 1 - Batik Collection -->
                <div class="swiper-slide relative">
                    <img src="image/banner/batik.png" 
                         class="w-full h-full object-cover" 
                         alt="Koleksi Batik TokoKu"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Koleksi Batik Premium</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Elegan, Autentik, Warisan Budaya Indonesia</p>
                            <a href="{{ route('products.list') }}?category_id=1" class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                Jelajahi Batik
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Slide 2 - Kaos Lokal -->
                <div class="swiper-slide relative">
                    <img src="image/banner/kaos.png" 
                         class="w-full h-full object-cover" 
                         alt="Kaos Lokal TokoKu"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Kaos Lokal Kreasi Anak Bangsa</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Nyaman, Trendi, Desain Kekinian</p>
                            <a href="{{ route('products.list') }}?category_id=2" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                Koleksi Kaos
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Slide 3 - Tas Handmade -->
                <div class="swiper-slide relative">
                    <img src="image/banner/tas.png" 
                         class="w-full h-full object-cover" 
                         alt="Tas Handmade TokoKu"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Tas Handmade Eksklusif</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Karya Tangan Pengrajin Terbaik Indonesia</p>
                            <a href="{{ route('products.list') }}?category_id=3" class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                Lihat Koleksi Tas
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Slide 4 - Sepatu Lokal -->
                <div class="swiper-slide relative">
                    <img src="image/banner/sepatu.png" 
                         class="w-full h-full object-cover" 
                         alt="Sepatu Lokal TokoKu"
                         loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                        <div class="container mx-auto px-4 md:px-8 text-white">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Sepatu Lokal Berkualitas</h2>
                            <p class="text-sm md:text-lg lg:text-xl mb-4 md:mb-6">Fashionable, Tahan Lama, Buatan Indonesia</p>
                            <a href="{{ route('products.list') }}?category_id=4" class="inline-block bg-red-600 hover:bg-red-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-medium transition duration-300 text-sm md:text-base">
                                Temukan Sepatu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pagination & Navigation -->
            <div class="swiper-pagination !bottom-2 md:!bottom-4"></div>
            <div class="swiper-button-next !hidden md:!flex !text-white !w-8 !h-8 md:!w-10 md:!h-10"></div>
            <div class="swiper-button-prev !hidden md:!flex !text-white !w-8 !h-8 md:!w-10 md:!h-10"></div>
        </div>
    </div>
    <!-- Value Propositions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="bg-blue-50 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Produk Asli</h3>
                <p class="text-xs text-gray-500">100% Original</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="bg-green-50 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Gratis Ongkir</h3>
                <p class="text-xs text-gray-500">Min. Belanja Rp300k</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="bg-amber-50 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Garansi</h3>
                <p class="text-xs text-gray-500">Pengembalian 14 Hari</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="bg-purple-50 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Bantuan</h3>
                <p class="text-xs text-gray-500">24/7 Customer Service</p>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Kategori Pilihan</h2>
            <a href="{{ route('products.list') }}" class="text-sm md:text-base text-blue-600 hover:text-blue-800 font-medium flex items-center">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('products.list') }}?category_id={{ $category->id }}" class="group relative overflow-hidden rounded-xl bg-white shadow-sm hover:shadow-md transition duration-300 border border-gray-100">
                    <div class="aspect-square overflow-hidden">
                        @if($category->foto)
                            <img src="{{ asset('storage/' . $category->foto) }}" 
                                 alt="{{ $category->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 text-center group-hover:text-blue-600 transition duration-300">{{ $category->name }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Latest Products -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Produk Terbaru</h2>
            <a href="{{ route('products.list') }}" class="text-sm md:text-base text-blue-600 hover:text-blue-800 font-medium flex items-center">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($latestProducts as $product)
                <div class="group relative bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 border border-gray-100">
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <div class="aspect-square overflow-hidden relative">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy">
                            @if($product->created_at > now()->subDays(7))
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Baru</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex justify-between items-center">
                                <p class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @if($product->reviews->count() > 0)
                                    <div class="flex items-center text-amber-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-xs ml-1 text-gray-600">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="px-4 pb-4">
                        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg text-sm font-medium transition duration-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Featured Collection -->
    <section class="mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-6 md:mb-0 md:pr-8">
                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium mb-4">Koleksi Spesial</span>
                <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-4">Batik Modern<br>Untuk Gaya Harian</h2>
                <p class="text-gray-600 mb-6">Temukan koleksi batik kontemporer kami yang dirancang untuk kenyamanan sehari-hari tanpa kehilangan nilai budaya.</p>
                <a href="{{ route('products.list') }}?category_id=1" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition duration-300">
                    Lihat Koleksi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
            <div class="md:w-1/2">
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-xl overflow-hidden shadow-md">
                        <img src="https://source.unsplash.com/random/600x600/?batik,shirt" alt="Batik Shirt" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="grid gap-4">
                        <div class="rounded-xl overflow-hidden shadow-md">
                            <img src="https://source.unsplash.com/random/600x600/?batik,dress" alt="Batik Dress" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="rounded-xl overflow-hidden shadow-md">
                            <img src="https://source.unsplash.com/random/600x600/?batik,accessories" alt="Batik Accessories" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About TokoKu Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl overflow-hidden shadow-md">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2">
                    <img src="https://source.unsplash.com/random/800x600/?indonesian,craftsman" 
                         alt="Pengrajin Lokal" 
                         class="w-full h-full object-cover"
                         loading="lazy">
                </div>
                <div class="md:w-1/2 p-8 md:p-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Tentang TokoKu</h2>
                    <p class="text-gray-600 mb-6">
                        TokoKu adalah platform e-commerce yang berkomitmen untuk mempromosikan produk fashion lokal Indonesia. Kami bekerja sama langsung dengan pengrajin batik, penenun, dan perajin lainnya di seluruh Nusantara untuk menghadirkan produk berkualitas dengan desain yang modern.
                    </p>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 mb-2">{{ $categories->count() }}+</div>
                            <div class="text-sm text-gray-600">Kategori Produk</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 mb-2">50+</div>
                            <div class="text-sm text-gray-600">Pengrajin Mitra</div>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        Pelajari lebih lanjut tentang kami
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">Apa Kata Pelanggan Kami</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="flex items-center text-amber-400 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500">2 hari lalu</span>
                </div>
                <p class="text-gray-600 mb-4">"Kualitas batiknya sangat bagus, bahannya nyaman dipakai. Pengiriman juga cepat. Akan belanja lagi di sini!"</p>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 mr-3 overflow-hidden">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Dian Sastro</h4>
                        <p class="text-xs text-gray-500">Jakarta</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="flex items-center text-amber-400 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500">1 minggu lalu</span>
                </div>
                <p class="text-gray-600 mb-4">"Sangat puas dengan produk tas anyaman dari TokoKu. Desainnya unik dan kualitasnya premium. Packagingnya juga rapi."</p>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 mr-3 overflow-hidden">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Rizky Ramadhan</h4>
                        <p class="text-xs text-gray-500">Bandung</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="flex items-center text-amber-400 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500">3 minggu lalu</span>
                </div>
                <p class="text-gray-600 mb-4">"Pengalaman belanja yang menyenangkan. Produk sesuai gambar, ukuran pas, dan harganya sangat kompetitif untuk kualitas premium."</p>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 mr-3 overflow-hidden">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Sarah Wijaya</h4>
                        <p class="text-xs text-gray-500">Surabaya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Dapatkan Update & Promo Spesial</h2>
        <p class="max-w-lg mx-auto mb-6 text-blue-100">Berlangganan newsletter kami untuk mendapatkan informasi produk terbaru, diskon eksklusif, dan tips gaya dengan fashion lokal.</p>
        <form class="max-w-md mx-auto flex">
            <input type="email" placeholder="Alamat email Anda" class="flex-grow px-4 py-3 rounded-l-lg focus:outline-none text-gray-800">
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 px-6 py-3 rounded-r-lg font-medium transition duration-300">
                Berlangganan
            </button>
        </form>
    </section>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
<style>
.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-button-next, 
.swiper-button-prev {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
    display: none;
}

@media (min-width: 768px) {
    .swiper-button-next, 
    .swiper-button-prev {
        display: flex;
    }
}

.swiper-button-next::after, 
.swiper-button-prev::after {
    font-size: 1.2rem;
    font-weight: bold;
}

.swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: white;
    opacity: 0.7;
}

.swiper-pagination-bullet-active {
    opacity: 1;
    background: #3b82f6;
}

/* Product card hover effect */
.group:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Line clamp for product names */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Aspect ratio for product images */
.aspect-square {
    aspect-ratio: 1/1;
}

/* Smooth transitions */
.transition {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Custom shadow */
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

/* Gradient overlay for images */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Custom rounded corners */
.rounded-xl {
    border-radius: 0.75rem;
}

.rounded-2xl {
    border-radius: 1rem;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Banner Swiper
    const bannerSwiper = new Swiper(".bannerSwiper", {
        loop: true,
        autoplay: {
            delay: 7000,
            pauseOnMouseEnter: true,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    // Pause on hover
    const bannerContainer = document.querySelector('.bannerSwiper');
    if (bannerContainer) {
        bannerContainer.addEventListener('mouseenter', () => {
            bannerSwiper.autoplay.stop();
        });
        bannerContainer.addEventListener('mouseleave', () => {
            bannerSwiper.autoplay.start();
        });
    }
});
</script>
@endsection