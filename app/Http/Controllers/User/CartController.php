<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::with('product')->where('user_id', auth()->id())->get();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);
        return view('user.cart.index', compact('items', 'total'));
    }
    
    public function add(Request $request, Product $product)
    {
        $item = CartItem::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['quantity' => 1]
        );
    
        if (!$item->wasRecentlyCreated) {
            $item->increment('quantity');
        }
    
        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }
    
    public function remove(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);
        $cartItem->delete();
    
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
    
}
