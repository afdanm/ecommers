@extends('layouts.admin')


@section('content')
    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dashboard Section -->
            <div class="bg-white shadow-sm rounded-lg mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Welcome to the Admin Dashboard!</h3>
                <p class="text-gray-500 mt-2">Manage your products, view transactions, and monitor system activity.</p>
            </div>

            <!-- Action Buttons Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Button - Go to Product Management -->
                <div class="bg-white p-6 shadow rounded-lg text-center">
                    <h4 class="text-xl font-semibold text-gray-700">Product Management</h4>
                    <p class="mt-2 text-gray-600">Manage and update your product listings</p>
                    <a href="{{ route('admin.products.index') }}"
                        class="mt-4 inline-block px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Go to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection