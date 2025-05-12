@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Profil Saya</h1>
                <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-50 transition">Edit Profil</a>
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-shrink-0">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div class="w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-gray-200">
                            <span class="text-4xl font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex-grow">
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->role }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Username</p>
                                <p class="font-medium">{{ $user->username }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nomor Telepon</p>
                                <p class="font-medium">{{ $user->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Alamat</p>
                                <p class="font-medium">{{ $user->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection