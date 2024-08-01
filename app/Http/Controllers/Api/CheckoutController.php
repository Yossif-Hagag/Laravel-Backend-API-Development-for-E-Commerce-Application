<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    use ApiResponseTrait;

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = $user->carts()->first();
        if (!$cart || $cart->products()->count() == 0) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "No Items In The Cart To Checkout");
        }

        $totalPrice = 0;
        foreach ($cart->products as $product) {
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        foreach ($cart->products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'price' => $product->price,
            ]);
        }

        $cart->products()->detach();

        return $this->apiResponse(data: $order->load('orderItems.product'), status: Response::HTTP_OK, message: "Order Created Successfully");
    }
}
