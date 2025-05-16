@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Edit Kategori</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
    
        <div>
            <label for="name">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" required>
        </div>
    
        <div>
            <label for="foto">Foto Kategori</label>
            <input type="file" name="foto" id="foto" accept="image/*">
            @if($category->foto)
                <img src="{{ asset('storage/' . $category->foto) }}" alt="Foto Kategori" class="w-24 mt-2">
            @endif
        </div>
    
        <button type="submit">Update Kategori</button>
    </form>
    
@endsection
