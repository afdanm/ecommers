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

public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:diproses,dikirim,selesai'
    ]);

    $transaction = Transaction::findOrFail($id);
    $transaction->update([
        'status' => $request->status
    ]);

    return redirect()->route('admin.transactions.index')->with('success', 'Status transaksi diperbarui.');
}
}
