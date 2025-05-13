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
        $carts = Cart::where('user_id', Auth::id())->get();
        $cartCount = $carts->count();
        return view('user.cart.index', compact('carts', 'cartCount'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:' . $product->stock
        ]);

        // Cek apakah produk sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($existingCart) {
            // Update quantity jika tidak melebihi stok
            $newQty = $existingCart->quantity + $request->qty;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
            $existingCart->update(['quantity' => $newQty]);
        } else {
            // Tambahkan ke keranjang jika belum ada
            if ($request->qty > $product->stock) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
            
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->qty,
            ]);
        }

       

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove(Cart $cart)
    {
        // Kembalikan stok produk sebelum dihapus
        $cart->product->increment('stock', $cart->quantity);
        
        $cart->delete();
        $cartCount = Cart::where('user_id', auth()->id())->count();
    
        return redirect()->route('cart.index')
            ->with('cartCount', $cartCount)
            ->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}