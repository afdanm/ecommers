<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
public function index()
{
    // Load relasi 'products' agar bisa dipakai di view
    $transactions = Transaction::with('products')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('user.transactions.index', compact('transactions'));
}
} 