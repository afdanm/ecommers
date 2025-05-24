<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    // Update status transaksi (status)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai',
        ]);

        $transaction = Transaction::findOrFail($id);

        // Kalau metode pickup, jangan update status manual via form
        if ($transaction->purchase_method === 'pickup') {
            return redirect()->route('admin.transactions.index')->with('error', 'Transaksi pickup otomatis selesai dan tidak bisa diubah.');
        }

        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('admin.transactions.index')->with('success', 'Status transaksi diperbarui.');
    }

    // Update status pengiriman (shipping_status)
    public function updateShippingStatus(Request $request, $id)
    {
        $request->validate([
            'shipping_status' => 'required|in:diproses,dikirim,selesai',
        ]);

        $transaction = Transaction::findOrFail($id);

        // Shipping status hanya untuk delivery
        if ($transaction->purchase_method !== 'delivery') {
            return redirect()->route('admin.transactions.index')->with('error', 'Status pengiriman hanya untuk metode delivery.');
        }

        $transaction->shipping_status = $request->shipping_status;
        $transaction->save();

        return redirect()->route('admin.transactions.index')->with('success', 'Status pengiriman diperbarui.');
    }
}
