<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product', 'size'])->where('user_id', Auth::id())->get();
        $cartCount = $carts->count();
        
        // Calculate total price
        $totalPrice = $carts->sum(function($cart) {
            return $cart->product->price * $cart->quantity;
        });
        
        return view('user.cart.index', compact('carts', 'cartCount', 'totalPrice'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'size_id' => 'nullable|exists:sizes,id'
        ]);

        // Check stock based on size or general stock
        if ($request->has('size_id')) {
            $size = $product->sizes()->where('size_id', $request->size_id)->first();
            if (!$size || $size->pivot->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi untuk ukuran yang dipilih!');
            }
        } else {
            if ($product->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
        }

        // Check if product already in cart with same size
        $existingCart = Cart::where('user_id', Auth::id())
                          ->where('product_id', $product->id)
                          ->where('size_id', $request->size_id)
                          ->first();

        if ($existingCart) {
            $newQty = $existingCart->quantity + $request->qty;
            
            // Check stock again for existing cart item
            $maxQty = $request->has('size_id') 
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

        // Check stock
        $maxQty = $cart->size_id 
            ? $cart->product->sizes()->where('size_id', $cart->size_id)->first()->pivot->stock
            : $cart->product->stock;

        if ($request->quantity > $maxQty) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        $cartCount = Cart::where('user_id', auth()->id())->count();
    
        return redirect()->route('cart.index')
            ->with('cartCount', $cartCount)
            ->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}