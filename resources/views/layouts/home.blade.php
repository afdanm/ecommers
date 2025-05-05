<!-- resources/views/layouts/home.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Bank - E-commerce</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg py-4 sticky top-0 z-50">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 flex items-center">
                <i class="fas fa-store mr-2"></i> MiniStore
            </a>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
                <a href="{{ route('products.list') }}" class="text-gray-700 hover:text-blue-600 font-medium">Shop</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Categories</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">About</a>
            </div>
            
            <div class="flex items-center space-x-6">
                <a href="#" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-search text-lg"></i>
                </a>
                <a href="#" class="text-gray-700 hover:text-blue-600 relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-user-circle text-lg"></i>
                    </a>
                    <a href="{{ route('logout') }}" class="text-gray-700 hover:text-blue-600 ml-2">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">MiniStore</h3>
                    <p class="text-gray-400">Your one-stop shop for all your needs. Quality products at affordable prices.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shop</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Customer Service</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">My Account</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Order Tracking</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Wishlist</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shipping Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Returns & Refunds</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Contact Us</h4>
                    <address class="text-gray-400 not-italic">
                        <p class="mb-2">123 Store Street, City</p>
                        <p class="mb-2">Phone: (123) 456-7890</p>
                        <p class="mb-2">Email: info@ministore.com</p>
                    </address>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; 2025 MiniStore. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <img src="https://via.placeholder.com/40x25" alt="Visa" class="h-6">
                    <img src="https://via.placeholder.com/40x25" alt="Mastercard" class="h-6">
                    <img src="https://via.placeholder.com/40x25" alt="PayPal" class="h-6">
                </div>
            </div>
        </div>
    </footer>

</body>
</html>