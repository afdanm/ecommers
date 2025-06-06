@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Tambah Kategori Baru</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700">Foto Kategori</label>
            <input type="file" name="foto" id="foto" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer focus:outline-none">
        </div>

        <button type="submit"
                class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg">
            Simpan Kategori
        </button>
    </form>
@endsection
