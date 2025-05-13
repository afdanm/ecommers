<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class AdminTransactionController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        // Mengambil transaksi dengan relasi user dan mengurutkannya berdasarkan tanggal terbaru
        $transactions = Transaction::with('user')->latest()->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    // Mengupdate status transaksi
public function update(Request $request, $id)
{
    $request->validate([
        'shipping_status' => 'required|in:diproses,dikirim,selesai'
    ]);

    $transaction = Transaction::findOrFail($id);

    $transaction->update([
        'shipping_status' => $request->shipping_status
    ]);

    return redirect()->route('admin.transactions.index')->with('success', 'Status pengiriman diperbarui.');
}


    // Mengupdate status pengiriman transaksi
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status pengiriman
        $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai'
        ]);

        // Mencari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($id);

        // Update status pengiriman
        $transaction->update([
            'status' => $request->status
        ]);

        // Kirim notifikasi sukses ke halaman admin
        return redirect()->route('admin.transactions.index')->with('success', 'Status pengiriman transaksi diperbarui.');
    }

    // Update status pengiriman
public function updateShippingStatus(Request $request, $id)
{
    $request->validate([
        'shipping_status' => 'required|in:diproses,dikirim,selesai'
    ]);

    $transaction = Transaction::findOrFail($id);
    $transaction->shipping_status = $request->shipping_status;
    $transaction->save();

    return redirect()->route('admin.transactions.index')->with('success', 'Status pengiriman diperbarui.');
}

}
