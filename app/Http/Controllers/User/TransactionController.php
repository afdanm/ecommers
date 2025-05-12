<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil transaksi yang sudah dilakukan oleh pengguna yang login
        $transactions = Transaction::where('user_id', Auth::id())->get();
        return view('user.transactions.index', compact('transactions'));
    }
}
