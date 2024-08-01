<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WishlistsController extends Controller
{
    use ApiResponseTrait;

    public function addProductToWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $user = auth()->user();
        $wishlist = $user->wishlists()->firstOrCreate(['product_id' => $request->product_id]);

        $product = Product::find($request->product_id);
        $wishlist->products()->syncWithoutDetaching([$product->id]);

        return $this->apiResponse(data: $wishlist->load('products'), status: Response::HTTP_OK, message: "Product Added To Wishlist Successfully");
    }


    public function removeProductFromWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $user = auth()->user();
        $wishlist = $user->wishlists()->first();

        if (!$wishlist) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Wishlist Not Found");
        }

        $product = Product::find($request->product_id);
        $wishlistProduct = $wishlist->products()->where('product_id', $request->product_id)->first();

        if (!$wishlistProduct) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Product Not Found In The Wishlist");
        }

        $wishlist->products()->detach($product->id);
        return $this->apiResponse(data: $wishlist->load('products'), status: Response::HTTP_OK, message: "Product Removed From Wishlist Successfully");
    }
}
