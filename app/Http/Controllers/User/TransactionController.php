<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('products')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.transactions.index', compact('transactions'));
    }

    /**
     * Method untuk membuat transaksi baru
     * Contoh: Simpan transaksi dengan logika pickup otomatis selesai
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_method' => 'required|in:pickup,delivery',
            'total_price' => 'required|numeric|min:0',
            'delivery_address' => 'nullable|string',
            // validasi lain sesuai kebutuhan
        ]);

        $transaction = new Transaction();
        $transaction->user_id = Auth::id();
        $transaction->purchase_method = $request->purchase_method;
        $transaction->total_price = $request->total_price;
        $transaction->delivery_address = $request->purchase_method === 'delivery' ? $request->delivery_address : null;

        // Otomatis set status jika pickup
        if ($transaction->purchase_method === 'pickup') {
            $transaction->status = 'selesai';
            $transaction->shipping_status = 'selesai';
        } else {
            $transaction->status = 'diproses';
            $transaction->shipping_status = 'diproses';
        }

        $transaction->save();

        // Simpan relasi produk, transaksi detail, dsb. sesuai aplikasi kamu

        return redirect()->route('user.transactions.index')->with('success', 'Transaksi berhasil dibuat.');
    }
}
