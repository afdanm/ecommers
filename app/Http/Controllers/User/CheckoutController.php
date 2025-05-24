<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product', 'size'])
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        foreach ($carts as $cart) {
            if ($cart->quantity > $cart->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Jumlah produk melebihi stok.');
            }
        }

        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        return view('user.checkout.index', compact('carts', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'purchase_method' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:purchase_method,delivery|string|max:500',
        ]);

        $user = Auth::user();
        $purchaseMethod = $request->purchase_method;
        $deliveryAddress = $purchaseMethod === 'delivery' ? $request->delivery_address : null;

        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        foreach ($carts as $cart) {
            if ($cart->quantity > $cart->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Stok produk "' . $cart->product->name . '" tidak mencukupi.');
            }
        }

        $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'ORDER-' . $user->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan purchase_method dan delivery_address ke session
        session([
            'purchase_method' => $purchaseMethod,
            'delivery_address' => $deliveryAddress,
            'order_id' => $orderId,
        ]);

        return view('user.checkout.payment', compact('snapToken', 'carts', 'total', 'orderId', 'purchaseMethod'));
    }

    public function paymentSuccess(Request $request)
    {
        $user = Auth::user();
        $orderId = session('order_id');
        $purchaseMethod = session('purchase_method', 'pickup');
        $deliveryAddress = session('delivery_address', null);

        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        foreach ($carts as $cart) {
            if ($cart->quantity > $cart->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Stok produk "' . $cart->product->name . '" tidak mencukupi.');
            }
        }

        $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'status' => 'paid',
                'midtrans_order_id' => $orderId,
                'purchase_method' => $purchaseMethod,
                'delivery_address' => $deliveryAddress,
                'payment_status' => 'paid',
            ]);

            foreach ($carts as $cart) {
                $cart->product->decrement('stock', $cart->quantity);
                $transaction->products()->attach($cart->product_id, [
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            session()->forget(['purchase_method', 'delivery_address', 'order_id']);

            return redirect()->route('transaction-history.index')->with('success', 'Pembayaran berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function error()
    {
        return view('user.checkout.error');
    }

    public function retry(Transaction $transaction)
{
    $user = auth()->user();

    if ($transaction->user_id !== $user->id) {
        abort(403, 'Unauthorized');
    }

    if ($transaction->status === 'paid') {
        return redirect()->route('checkout.success')->with('success', 'Transaksi sudah berhasil dibayar.');
    }

    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => $transaction->midtrans_order_id,
            'gross_amount' => $transaction->total_price,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    return view('user.checkout.payment', [
        'snapToken' => $snapToken,
        'total' => $transaction->total_price,
        'orderId' => $transaction->midtrans_order_id,
        'purchaseMethod' => $transaction->purchase_method,
        // Jika kamu butuh info lain seperti carts bisa di sesuaikan
    ]);
}

}
