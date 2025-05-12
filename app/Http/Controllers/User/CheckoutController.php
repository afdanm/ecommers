<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Display the checkout page for the user.
     *
     * @return \Illuminate\View\View
     */
/*******  14470663-30d4-4d99-a8c0-ec2aa59b19d9  *******/    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        // Validasi stok sebelum checkout
        foreach ($carts as $cart) {
            if ($cart->quantity > $cart->product->stock) {
                return redirect()->route('cart.index')
                    ->with('error', 'Stok produk "' . $cart->product->name . '" tidak mencukupi. Stok tersedia: ' . $cart->product->stock);
            }
        }

        $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

        if ($carts->isEmpty() || $total <= 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong atau total tidak valid.');
        }

        return view('user.checkout.index', compact('carts', 'total'));
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $carts = Cart::with('product')->where('user_id', Auth::id())->get();
            $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

            if ($carts->isEmpty() || $total <= 0) {
                return redirect()->route('cart.index')->with('error', 'Keranjang kosong atau total tidak valid.');
            }

            // Validasi stok lagi sebelum proses
            foreach ($carts as $cart) {
                if ($cart->quantity > $cart->product->stock) {
                    return redirect()->route('cart.index')
                        ->with('error', 'Stok produk "' . $cart->product->name . '" tidak mencukupi. Stok tersedia: ' . $cart->product->stock);
                }
            }

            // Membuat transaksi baru
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'status' => 'pending',
            ]);

            // Kurangi stok produk
            foreach ($carts as $cart) {
                $product = $cart->product;
                $product->stock -= $cart->quantity;
                $product->save();
            }

            // Midtrans Config
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->id . '-' . time(),
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            // Simpan order_id dari Midtrans
            $transaction->update(['midtrans_order_id' => $params['transaction_details']['order_id']]);

            // Menghapus item dari keranjang setelah transaksi berhasil
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Arahkan ke halaman pembayaran setelah sukses
            return view('user.checkout.payment', compact('snapToken', 'transaction'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Terjadi kesalahan saat proses checkout: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('user.checkout.success');
    }

    public function error()
    {
        return view('user.checkout.error');
    }
}