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
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        // Validasi stok
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
            $request->validate([
                'purchase_method' => 'required|in:pickup,delivery',
            ]);

            DB::beginTransaction();

            try {
                $user = Auth::user();
                $carts = Cart::with('product')->where('user_id', $user->id)->get();
                $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

                if ($carts->isEmpty() || $total <= 0) {
                    return redirect()->route('cart.index')->with('error', 'Keranjang kosong atau total tidak valid.');
                }

                // Validasi stok
                foreach ($carts as $cart) {
                    if ($cart->quantity > $cart->product->stock) {
                        return redirect()->route('cart.index')
                            ->with('error', 'Stok produk "' . $cart->product->name . '" tidak mencukupi. Tersisa: ' . $cart->product->stock);
                    }
                }

                // Cek alamat jika metode kirim
                $deliveryAddress = null;
                if ($request->purchase_method === 'delivery') {
                    if (empty($user->alamat)) {
                        return redirect()->route('profile.index')->with('error', 'Silakan lengkapi alamat di profil Anda terlebih dahulu.');
                    }
                    $deliveryAddress = $user->alamat;
                }

                // Buat transaksi
                $transaction = Transaction::create([
                    'user_id'           => $user->id,
                    'total_price'       => $total,
                    'status'            => 'pending',
                    'purchase_method'   => $request->purchase_method,
                    'delivery_address'  => $deliveryAddress,
                ]);

                // Kurangi stok produk
                foreach ($carts as $cart) {
                    $cart->product->decrement('stock', $cart->quantity);
                }

                // Midtrans
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = false; // Gunakan mode sandbox
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $orderId = $transaction->id . '-' . time();

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

                $transaction->update([
                    'midtrans_order_id' => $orderId,
                    'snap_token' => $snapToken,
                ]);

                // Kosongkan keranjang
                Cart::where('user_id', $user->id)->delete();

                DB::commit();

                // Redirect ke halaman pembayaran
                return view('user.checkout.payment', compact('snapToken', 'transaction'));

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('cart.index')
                    ->with('error', 'Terjadi kesalahan saat proses checkout: ' . $e->getMessage());
            }
        }


public function retry(Transaction $transaction)
{
    $user = Auth::user();

    // Pastikan transaksi milik user yang login
    if ($transaction->user_id !== $user->id) {
        abort(403, 'Unauthorized action.');
    }

    // Setup Midtrans
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // Buat order ID baru jika belum ada
    $orderId = $transaction->midtrans_order_id ?? ($transaction->id . '-' . time());

    // Siapkan parameter pembayaran
    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $transaction->total_price,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
        ],
    ];

    // Ambil snap token baru dari Midtrans
    $snapToken = Snap::getSnapToken($params);

    // Simpan data baru
    $transaction->update([
        'midtrans_order_id' => $orderId,
        'snap_token' => $snapToken,
    ]);

    return view('user.checkout.payment', compact('snapToken', 'transaction'));
}


public function success(Request $request)
{
    // Pastikan order_id tersedia
    if (!$request->has('order_id')) {
        return redirect()->route('transaction-history.index')
            ->with('error', 'ID Transaksi tidak ditemukan.');
    }

    // Cari transaksi berdasarkan order_id dari Midtrans
    $transaction = Transaction::where('midtrans_order_id', $request->order_id)->first();

    // Jika transaksi ditemukan dan belum berstatus paid, update statusnya
    if ($transaction && $transaction->status !== 'paid') {
        $transaction->status = 'paid';
        $transaction->save();
        
        // Tambahkan session flash untuk notifikasi
        session()->flash('success', 'Pembayaran berhasil diproses. Transaksi Anda telah berhasil.');
    }

    // Redirect langsung ke halaman riwayat transaksi setelah pembayaran berhasil
    return redirect()->route('transaction-history.index');
}


    public function error()
    {
        return view('user.checkout.error');
    }
}