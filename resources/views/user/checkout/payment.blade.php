@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Pembayaran</h1>
    <div id="snap-container"></div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    window.onload = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = '{{ route("checkout.success") }}?order_id={{ $transaction->midtrans_order_id }}';
            },
            onPending: function(result){
                alert("Transaksi belum selesai.");
            },
            onError: function(result){
                window.location.href = '{{ route("checkout.error") }}';
            },
            onClose: function(){
                alert("Anda menutup popup pembayaran.");
            }
        });
    };
</script>
@endsection
