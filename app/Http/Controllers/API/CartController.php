<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CartResource::collection($this->cart->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $user = Auth::user();
            $product = Product::findOrFail($validatedData['product_id']);
            $cart = new Cart($validatedData);
            $cart->user_id = $user->id;
            $cart->price = $product->price;
            $cart->save();
            $this->saveImage($request->image, 'carts', 300, 300);
            DB::commit();
            return new CartResource($cart);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->validated());

            DB::rollBack();
            return response()->json(['error' => 'Failed to create cart.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        try {
            return new CartResource($cart);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart Item not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, Cart $cart)
    {
        try {
            DB::beginTransaction();
            $cart->update($request->validated());
            $this->saveImage($request->image, 'carts', 300, 300);
            DB::commit();
            return new CartResource($cart);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->all());

            DB::rollBack();
            return response()->json(['error' => 'Failed to update cart.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        DB::beginTransaction();

        try {
            $cart->delete();
            DB::commit();
            return response()->json(['message' => 'Cart Item deleted successfully.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();
            return response()->json(['error' => 'Failed to delete cart.'], 500);
        }
    }

}
