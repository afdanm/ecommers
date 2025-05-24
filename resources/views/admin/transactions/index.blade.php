@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Transaksi</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">#</th>
                <th class="border border-gray-300 px-4 py-2">User</th>
                <th class="border border-gray-300 px-4 py-2">Harga Total</th>
                <th class="border border-gray-300 px-4 py-2">Metode</th>
                <th class="border border-gray-300 px-4 py-2">Status Transaksi</th>
                <th class="border border-gray-300 px-4 py-2">Status Pengiriman</th>
                <th class="border border-gray-300 px-4 py-2">Alamat</th>
                <th class="border border-gray-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $t->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $t->user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($t->total_price) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($t->purchase_method) }}</td>

                    <!-- Status transaksi -->
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($t->purchase_method === 'pickup')
                            <span class="text-green-600 font-semibold">Selesai</span>
                        @else
                            <form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="diproses" {{ $t->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $t->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="selesai" {{ $t->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        @endif
                    </td>

                    <!-- Status pengiriman -->
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($t->purchase_method === 'delivery')
                            <form action="{{ route('admin.transactions.updateShippingStatus', $t->id) }}" method="POST">
                                @csrf
                                <select name="shipping_status" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="diproses" {{ $t->shipping_status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                    <option value="dikirim" {{ $t->shipping_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="selesai" {{ $t->shipping_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        @elseif ($t->purchase_method === 'pickup')
                            <span class="text-green-600 font-semibold">Selesai</span>
                        @else
                            -
                        @endif
                    </td>

                    <td class="border border-gray-300 px-4 py-2">{{ $t->delivery_address ?? '-' }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <!-- Bisa tambah tombol aksi lain -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
