@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Daftar Kategori</h1>

    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md mb-4">Tambah Kategori</a>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-4 border-b">ID</th>
                <th class="px-6 py-4 border-b">Nama Kategori</th>
                <th class="px-6 py-4 border-b">Gambar</th>
                <th class="px-6 py-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 border-b">{{ $category->id }}</td>
                    <td class="px-6 py-4 border-b">{{ $category->name }}</td>
                    <td class="px-6 py-4 border-b">
                        @if($category->foto)
                        <img src="{{ asset('storage/' . $category->foto) }}" alt="Gambar Kategori" class="w-32 h-32 object-cover">
                    @else
                        <p>Gambar tidak tersedia</p>
                    @endif                    
                    </td>
                    <td class="px-6 py-4 border-b">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-yellow-500 hover:text-yellow-600">Edit</a> |
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
