<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product', 'size'])->where('user_id', Auth::id())->get();

        $cartCount = $carts->count();
        $totalPrice = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        return view('user.cart.index', compact('carts', 'cartCount', 'totalPrice'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'size_id' => 'nullable|exists:sizes,id'
        ]);

        // Stock check
        if ($request->size_id) {
            $sizeStock = $product->sizes()->where('size_id', $request->size_id)->first()->pivot->stock;
            if ($sizeStock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi untuk ukuran yang dipilih!');
            }
        } else {
            if ($product->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
        }

        // Check existing cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('size_id', $request->size_id)
            ->first();

        if ($existingCart) {
            $newQty = $existingCart->quantity + $request->qty;
            $maxQty = $request->size_id
                ? $product->sizes()->where('size_id', $request->size_id)->first()->pivot->stock
                : $product->stock;

            if ($newQty > $maxQty) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }

            $existingCart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'size_id' => $request->size_id,
                'quantity' => $request->qty,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

  public function update(Request $request, Cart $cart)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    // Pastikan cart milik user yang sedang login
    if ($cart->user_id !== auth()->id()) {
        abort(403);
    }

    $quantity = (int) $request->quantity;

    // Ambil stok produk, apakah berdasarkan size atau produk biasa
    $stockTotal = $cart->size_id
        ? $cart->product->sizes()->where('size_id', $cart->size_id)->first()->pivot->stock
        : $cart->product->stock;

    // Hitung quantity produk yang sudah ada di cart user selain cart ini
    $cartQtyOther = Cart::where('user_id', auth()->id())
        ->where('product_id', $cart->product_id)
        ->when($cart->size_id, function($q) use ($cart) {
            return $q->where('size_id', $cart->size_id);
        }, function($q) {
            return $q->whereNull('size_id');
        })
        ->where('id', '!=', $cart->id)
        ->sum('quantity');

    // Stok yang tersedia setelah dikurangi quantity lain di cart
    $stockAvailable = $stockTotal - $cartQtyOther;

    // Validasi stok
    if ($quantity > $stockAvailable) {
        return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
    }

    // Update quantity di cart
    $cart->update(['quantity' => $quantity]);

    // Optional: pesan beda untuk tambah atau kurang
    $action = $request->input('action');
    if ($action == 'increment') {
        $msg = 'Jumlah produk berhasil ditambah';
    } elseif ($action == 'decrement') {
        $msg = 'Jumlah produk berhasil dikurangi';
    } else {
        $msg = 'Keranjang berhasil diperbarui';
    }

    return back()->with('success', $msg);
}


    public function remove(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
