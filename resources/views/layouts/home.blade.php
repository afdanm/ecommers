<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TokoKu - Fashion Lokal Indonesia</title>
    <meta name="description" content="TokoKu menyediakan produk fashion lokal berkualitas seperti batik, kaos lokal, tas handmade, dan sepatu lokal karya UMKM Indonesia">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
</head>

<body class="font-sans bg-gray-50 antialiased">
   
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4 sticky top-0 z-40 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-amber-600 flex items-center">
                <i class="fas fa-tshirt mr-2"></i> TokoKu
            </a>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200">Beranda</a>
                <a href="{{ route('products.list') }}" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200">Produk</a>
                <div class="relative group">
                    <button class="text-gray-700 hover:text-amber-600 font-medium flex items-center transition-colors duration-200">
                        Kategori <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Baju Batik</a>
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Kaos Lokal</a>
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Tas Handmade</a>
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Sepatu Lokal</a>
                    </div>
                </div>
                <a href="#" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200">Tentang Kami</a>
            </div>

            <div class="flex items-center space-x-6">
                <div class="relative group">
                    <form action="{{ route('products.list') }}" method="GET" class="flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk..." 
                            class="px-3 py-1.5 border rounded-full text-sm w-40 md:w-48 focus:w-56 transition-all duration-300 focus:outline-none focus:ring-1 focus:ring-amber-500"/>
                        <button type="submit" class="ml-2 text-gray-700 hover:text-amber-600 transition-colors duration-200">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                    </form>
                </div>

                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-amber-600 relative transition-colors duration-200 transform hover:scale-110">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="relative group">
                        <a href="{{ route('profile.index') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200 transform hover:scale-110">
                            <i class="fas fa-user-circle text-lg"></i>
                        </a>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Profil Saya</a>
                            <a href="{{ route('transaction-history.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Riwayat Transaksi</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-amber-50 hover:text-amber-600 transition-colors duration-200">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Login</a>
                    <a href="{{ route('register') }}" class="bg-amber-600 text-white px-4 py-2 rounded-full hover:bg-amber-700 transition-colors duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="md:hidden fixed bottom-6 right-6 bg-amber-600 text-white rounded-full p-4 z-30 shadow-lg transform hover:scale-110 transition-transform duration-200">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-white z-40 flex flex-col items-center justify-center hidden transition-opacity duration-300">
        <button id="close-mobile-menu" class="absolute top-6 right-6 text-gray-700 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex flex-col items-center space-y-8 text-lg">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Beranda</a>
            <a href="{{ route('products.list') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Produk</a>
            <a href="#" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Kategori</a>
            <a href="#" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Tentang Kami</a>
            @auth
                <a href="{{ route('profile.index') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Profil Saya</a>
                <a href="{{ route('transaction-history.index') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Riwayat Transaksi</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-600 transition-colors duration-200">Login</a>
                <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 transition-colors duration-200">Daftar</a>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6281234567890" target="_blank" class="fixed bottom-6 left-6 bg-green-500 text-white rounded-full p-3 z-30 shadow-lg transform hover:scale-110 transition-transform duration-200 animate-bounce">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-20 md:right-6 bg-gray-800 text-white rounded-full p-3 z-30 shadow-lg opacity-0 invisible transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-tshirt mr-2 text-amber-500"></i> TokoKu
                    </h3>
                    <p class="text-gray-400 mb-4">Menyediakan produk fashion lokal berkualitas untuk mendukung UMKM Indonesia.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200"><i class="fab fa-tiktok text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200"><i class="fab fa-youtube text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Produk Kami</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Baju Batik</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Kaos Lokal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Tas Handmade</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Sepatu Lokal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Produk Baru</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Bantuan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Cara Belanja</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Pengiriman</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Pembayaran</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">Pengembalian</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Hubungi Kami</h4>
                    <address class="text-gray-400 not-italic">
                        <p class="mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-amber-500"></i> Jl. Fashion No. 123, Jakarta
                        </p>
                        <p class="mb-2 flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-amber-500"></i> (021) 1234-5678
                        </p>
                        <p class="mb-2 flex items-center">
                            <i class="fas fa-envelope mr-2 text-amber-500"></i> info@tokoku.com
                        </p>
                        <p class="mb-2 flex items-center">
                            <i class="fas fa-clock mr-2 text-amber-500"></i> Buka: 09:00 - 17:00
                        </p>
                    </address>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">&copy; 2025 TokoKu. Seluruh hak dilindungi.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <img src="{{ asset('images/payment-bca.png') }}" alt="BCA" class="h-8">
                    <img src="{{ asset('images/payment-mandiri.png') }}" alt="Mandiri" class="h-8">
                    <img src="{{ asset('images/payment-bri.png') }}" alt="BRI" class="h-8">
                    <img src="{{ asset('images/payment-gopay.png') }}" alt="Gopay" class="h-8">
                    <img src="{{ asset('images/payment-ovo.png') }}" alt="OVO" class="h-8">
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.classList.add('opacity-100');
            }, 10);
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('opacity-100');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        });

        // Back to top button
        const backToTopButton = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 50) {
                navbar.classList.add('shadow-md', 'py-3');
                navbar.classList.remove('shadow-sm', 'py-4');
            } else {
                navbar.classList.remove('shadow-md', 'py-3');
                navbar.classList.add('shadow-sm', 'py-4');
            }
        });

        // Initialize Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const bannerSwiper = new Swiper('.bannerSwiper', {
                loop: true,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
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

        // Product hover animation
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.querySelector('img').classList.add('scale-105');
            });
            card.addEventListener('mouseleave', () => {
                card.querySelector('img').classList.remove('scale-105');
            });
        });
    </script>
    @yield('scripts')
</body>
</html>