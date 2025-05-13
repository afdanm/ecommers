<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Include Tailwind CSS & JS -->
</head>

<body class="font-sans antialiased">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white min-h-screen p-5">
            <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
            <ul>
                <li class="mb-4">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center text-gray-300 hover:text-white">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.products.index') }}"
                        class="flex items-center text-gray-300 hover:text-white">
                        <i class="fas fa-box mr-2"></i>
                        Produk
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center text-gray-300 hover:text-white">
                        <i class="fas fa-cogs mr-2"></i>
                        Kategori
                    </a>
                </li>
               <li class="mb-4">
    <a href="{{ route('admin.transactions.index') }}" 
        class="flex items-center text-gray-300 hover:text-white">
        <i class="fas fa-shopping-cart mr-2"></i>
        Pesanan
    </a>
</li>
                <li class="mb-4">
                  <a href="{{ route('admin.reports.sales') }}"
   class="flex items-center text-gray-300 hover:text-white">
   <i class="fas fa-chart-line mr-2"></i>
   Laporan Penjualan
</a>
</li>

            </ul>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg mt-6">
                    Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            @yield('content') <!-- Konten dinamis -->
        </div>
    </div>
</body>

</html>
