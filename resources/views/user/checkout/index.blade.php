@extends('layouts.home')

@section('content')


<form action="{{ route('checkout.process') }}" method="POST">
    @csrf

    <label class="block mb-2">Pilih Metode Pembelian:</label>
    <select name="purchase_method" id="purchase_method" class="mb-4 border p-2 rounded" onchange="toggleAddress()">
        <option value="pickup">Ambil di Toko</option>
        <option value="delivery">Kirim ke Alamat</option>
    </select>

    <!-- Alamat hanya ditampilkan sebagai info jika user punya alamat -->
    <div id="address-section" class="mb-4 hidden">
        <label class="block">Alamat Tersimpan:</label>
            <p class="border p-2 rounded bg-gray-100">
                 {{ Auth::user()->alamat ?? 'Belum ada alamat' }}</p>
    </div>

    <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Lanjutkan ke Pembayaran</button>
</form>

<script>
    function toggleAddress() {
        const method = document.getElementById('purchase_method').value;
        const addressSection = document.getElementById('address-section');
        addressSection.classList.toggle('hidden', method !== 'delivery');
    }

    window.onload = toggleAddress;
</script>
@endsection
