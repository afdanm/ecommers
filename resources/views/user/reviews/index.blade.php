@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Produk
        </a>
    </div>

    <div class="flex items-center mb-8">
        <div class="mr-6">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded">
        </div>
        <div>
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
            <div class="flex items-center mt-1">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->averageRating()))
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @elseif($i == ceil($product->averageRating()) && $product->averageRating() - floor($product->averageRating()) > 0)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="half-star" x1="0" x2="100%" y1="0" y2="0">
                                        <stop offset="50%" stop-color="currentColor"/>
                                        <stop offset="50%" stop-color="#d1d5db"/>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half-star)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                    <span class="ml-2 text-gray-600">{{ number_format($product->averageRating(), 1) }} dari {{ $product->totalReviews() }} ulasan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($reviews as $review)
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="font-semibold">{{ $review->user->name }}</div>
                    <div class="flex ml-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                        @endfor
                    </div>
                </div>
                <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y H:i') }}</span>
            </div>
            <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>
@endsection