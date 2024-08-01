<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CartsController extends Controller
{
    use ApiResponseTrait;

    public function addProductToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $user = auth()->user();
        $cart = $user->carts()->first();
        if (!$cart) {
            $cart = $user->carts()->create(['user_id' => $user->id, 'product_id' => $request->product_id, 'quantity' => $request->quantity]);
        }

        $product = Product::find($request->product_id);
        $cart->products()->syncWithoutDetaching([
            $product->id => ['quantity' => $request->quantity]
        ]);

        return $this->apiResponse(data: $cart->load('products'), status: Response::HTTP_OK, message: "Product Added To Cart Successfully");
    }

    public function updateProductInCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $user = auth()->user();
        $cart = $user->carts()->first();
        if (!$cart) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Cart Not Found");
        }

        $product = Product::find($request->product_id);
        $cartProduct = $cart->products()->where('product_id', $request->product_id)->first();
        if (!$cartProduct) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Product Not Found In The Cart");
        }

        $cart->products()->updateExistingPivot($product->id, ['quantity' => $request->quantity]);
        return $this->apiResponse(data: $cart->load('products'), status: Response::HTTP_OK, message: "Product Quantity Updated Successfully");
    }

    public function removeProductFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $user = auth()->user();
        $cart = $user->carts()->first();
        if (!$cart) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Cart Not Found");
        }

        $product = Product::find($request->product_id);
        $cartProduct = $cart->products()->where('product_id', $request->product_id)->first();
        if (!$cartProduct) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Product Not Found In The Cart");
        }

        $cart->products()->detach($product->id);
        return $this->apiResponse(data: $cart->load('products'), status: Response::HTTP_OK, message: "Product Removed From Cart Successfully");
    }
}
