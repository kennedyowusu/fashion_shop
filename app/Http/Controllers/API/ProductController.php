<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        return ProductResource::collection($this->product->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = Product::create($request->validated());
            $this->saveImage($request->image, 'products', 300, 300);
            DB::commit();
            return new ProductResource($product);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->validated());

            DB::rollBack();
            return response()->json(['error' => 'Failed to create product.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            return new ProductResource($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            // Log the validated request data before attempting to update the products table
            Log::info('Validated Request Data: ' . json_encode($request->validated()));

            $product->update($request->validated());
            $this->saveImage($request->image, 'products', 300, 300);
            DB::commit();
            return new ProductResource($product);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->validated());

            DB::rollBack();
            return response()->json(['error' => 'Failed to update product.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            $product->delete();
            DB::commit();

            return response()->json(['message' => 'Product deleted.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();
            return response()->json(['error' => 'Failed to delete product.'], 500);
        }
    }

    public function categoryProducts(Category $category)
    {
        $products = $category->products;
        return response()->json($products);
    }
}
