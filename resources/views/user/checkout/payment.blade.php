{{-- resources/views/user/checkout/payment.blade.php --}}
@extends('layouts.home')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 text-center">
    <h1 class="text-2xl font-bold mb-6">Pembayaran</h1>

    <p>Total yang harus dibayar: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></p>

    <button id="pay-button" class="mt-6 bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
        Bayar Sekarang
    </button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                // redirect ke route payment success dengan order_id
                window.location.href = "{{ route('checkout.success') }}";
            },
            onPending: function(result){
                alert('Pembayaran pending, silakan selesaikan pembayaran.');
            },
            onError: function(result){
                alert('Pembayaran gagal, silakan coba lagi.');
                window.location.href = "{{ route('checkout.error') }}";
            },
            onClose: function(){
                alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran.');
            }
        });
    });
</script>
@endsection
