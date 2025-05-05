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
                <li class="mb-4"><a href="{{ route('admin.dashboard') }}" class="text-white">📊 Dashboard</a></li>
                <li class="mb-4"><a href="{{ route('admin.products.index') }}" class="text-white">📦 Manajemen
                        Produk</a></li>
                <li class="mb-4"><a href="#" class="text-white">🗂 Manajemen Kategori</a></li> <!-- Belum Selesai -->
                <li class="mb-4"><a href="#" class="text-white">🧾 Manajemen Pesanan</a></li> <!-- Belum Selesai -->
                <li class="mb-4"><a href="#" class="text-white">👥 Manajemen User</a></li> <!-- Belum Selesai -->
                <li class="mb-4"><a href="#" class="text-white">📲 Notifikasi WhatsApp</a></li> <!-- Belum Selesai -->
            </ul>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg">
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