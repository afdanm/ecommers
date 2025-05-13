@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Transaksi</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Harga Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>Rp {{ number_format($t->total_price) }}</td>
                    <td>{{ ucfirst($t->purchase_method) }}</td>
                    <td>{{ ucfirst($t->status) }}</td>
                    <td>{{ $t->delivery_address ?? '-' }}</td>
                    <td>
                        <!-- Form untuk mengubah status transaksi -->
                        <form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()">
                                <option {{ $t->status == 'diproses' ? 'selected' : '' }} value="diproses">Diproses</option>
                                <option {{ $t->status == 'dikirim' ? 'selected' : '' }} value="dikirim">Dikirim</option>
                                <option {{ $t->status == 'selesai' ? 'selected' : '' }} value="selesai">Selesai</option>
                            </select>
                        </form>

                        <!-- Tombol untuk update status pengiriman -->
<!-- Form untuk mengubah status pengiriman -->
<form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">
    @csrf @method('PATCH')
    <select name="shipping_status" onchange="this.form.submit()">
        <option {{ $t->shipping_status == 'diproses' ? 'selected' : '' }} value="diproses">Sedang Diproses</option>
        <option {{ $t->shipping_status == 'dikirim' ? 'selected' : '' }} value="dikirim">Dikirim</option>
        <option {{ $t->shipping_status == 'selesai' ? 'selected' : '' }} value="selesai">Selesai</option>
    </select>
</form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
