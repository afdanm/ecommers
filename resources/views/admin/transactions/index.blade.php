@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Transaksi</h1>

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
                        <form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()">
                                <option {{ $t->status == 'diproses' ? 'selected' : '' }} value="diproses">Diproses</option>
                                <option {{ $t->status == 'dikirim' ? 'selected' : '' }} value="dikirim">Dikirim</option>
                                <option {{ $t->status == 'selesai' ? 'selected' : '' }} value="selesai">Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
