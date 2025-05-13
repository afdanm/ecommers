@extends('layouts.home')

@section('content')

<div class="container mx-auto p-4 text-center">
    <h1 class="text-2xl font-bold mb-4">Pembayaran</h1>
    <p class="mb-6 text-lg">Silakan selesaikan pembayaran kamu.</p>

    <button id="pay-button" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Bayar Sekarang</button>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        let payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Pastikan order_id dikirim dengan benar
                    window.location.href = "{{ route('checkout.success') }}?order_id=" + result.order_id;
                },
                onPending: function(result){
                    window.location.href = "{{ route('checkout.error') }}";
                },
                onError: function(result){
                    window.location.href = "{{ route('checkout.error') }}";
                },
                onClose: function(){
                    // Do nothing
                }
            });
        });
    </script>
</div>
@endsection