<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartI;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{public function index()
    {
        $items = CartItem::with('product')->where('user_id', auth()->id())->get();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);
        return view('user.checkout.index', compact('items', 'total'));
    }
    
    public function process(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
    
        $order_id = 'TOKOKU-' . time();
        $total_price = CartItem::with('product')->where('user_id', auth()->id())
            ->get()
            ->sum(fn($item) => $item->product->price * $item->quantity);
    
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total_price,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        ];
    
        $snapToken = \Midtrans\Snap::getSnapToken($params);
    
        return view('user.checkout.payment', compact('snapToken', 'order_id'));
    }
    
    public function success(Request $request)
{
    $orderId = $request->query('order_id');
    $userId = auth()->id();

    $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
    $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

    $order = Order::create([
        'user_id' => $userId,
        'total_price' => $totalPrice,
        'status' => 'pending',
        'purchase_method' => session('purchase_method', 'pickup'),
        'delivery_address' => session('delivery_address'),
        'pickup_store' => null,
    ]);

    foreach ($cartItems as $item) {
        $order->items()->create([
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);
    }

    CartItem::where('user_id', $userId)->delete();

    return view('user.checkout.success', compact('order'));
}

}
