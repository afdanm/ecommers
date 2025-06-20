<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product', 'variant'])->where('user_id', Auth::id())->get();

        $cartCount = $carts->count();
        $totalPrice = $carts->sum(function($cart) {
            return ($cart->variant ? $cart->variant->price : $cart->product->price) * $cart->quantity;
        });

        return view('user.cart.index', compact('carts', 'cartCount', 'totalPrice'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id,product_id,'.$product->id
        ]);
    
        // Stock check
        if ($request->filled('variant_id')) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            if ($variant->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi untuk varian yang dipilih!');
            }
        } else {
            if ($product->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
        }
    
        // Check existing cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();
    
        if ($existingCart) {
            $newQty = $existingCart->quantity + $request->qty;
            $maxQty = $request->filled('variant_id')
                ? $variant->stock
                : $product->stock;
    
            if ($newQty > $maxQty) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
    
            $existingCart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $request->variant_id, // can be null
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

        // Ensure cart belongs to logged in user
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $quantity = (int) $request->quantity;

        // Get stock - either from variant or product
        $stockTotal = $cart->variant_id
            ? $cart->variant->stock
            : $cart->product->stock;

        // Calculate quantity of same product already in cart (excluding current cart item)
        $cartQtyOther = Cart::where('user_id', auth()->id())
            ->where('product_id', $cart->product_id)
            ->when($cart->variant_id, function($q) use ($cart) {
                return $q->where('variant_id', $cart->variant_id);
            }, function($q) {
                return $q->whereNull('variant_id');
            })
            ->where('id', '!=', $cart->id)
            ->sum('quantity');

        // Available stock after subtracting other cart quantities
        $stockAvailable = $stockTotal - $cartQtyOther;

        // Validate stock
        if ($quantity > $stockAvailable) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        // Update cart quantity
        $cart->update(['quantity' => $quantity]);

        // Optional: different messages for increment/decrement
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