<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    use ApiResponseTrait;

    public function products()
    {
        $products = Product::paginate('12');
        if ($products) {
            return $this->apiResponse(data: $products, status: Response::HTTP_OK, message: "All Products");
        }
        return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Product Not Found");
    }
    public function read(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            return $this->apiResponse(data: $product, status: Response::HTTP_OK, message: "Read Product");
        }
        return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: "Product Not Found");
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $product = Product::create($request->only(['name', 'description', 'price', 'quantity']));
        return $this->apiResponse(data: $product, status: Response::HTTP_CREATED, message: "Product Created Successfully");
    }
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: 'Product Not Found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(data: null, status: Response::HTTP_UNPROCESSABLE_ENTITY, message: $validator->errors()->first());
        }

        $product->update($request->only(['name', 'description', 'price', 'quantity']));
        return $this->apiResponse(data: $product, status: Response::HTTP_OK, message: "Product Updated Successfully");
    }
    public function delete(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(data: null, status: Response::HTTP_NOT_FOUND, message: 'Product Not Found');
        }

        $product->delete();
        return $this->apiResponse(data: $product, status: Response::HTTP_OK, message: "Product Deleted Successfully");
    }
}
