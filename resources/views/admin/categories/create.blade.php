@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Tambah Kategori Baru</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        
        <button type="submit" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg">Simpan Kategori</button>
    </form>
@endsection
