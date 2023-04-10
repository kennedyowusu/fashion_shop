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
use App\Actions\SaveImageAction;

class CartController extends Controller
{

    // A protected property $cart which is a reference to the Cart model.
    protected $cart;

    // In the constructor, the $cart property is set to an instance of the Cart model using dependency injection. This allows us to easily mock the Cart model when testing.
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    // This method returns a collection of all the cart items for the authenticated user.
    public function index()
    {
        $user = Auth::user();
        $carts = $this->cart->where('user_id', $user->id)->get();
        return CartResource::collection($carts);
    }

    // This method creates a new cart item for the authenticated user.
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
            // $cart->image = $this->saveImage($request->file('image'), 'carts', 300, 300);
            $action = new SaveImageAction();
            $cart->image = $action->execute($request->file('image'), 'carts', 300, 300);
            $cart->save();
            DB::commit();

            return new CartResource($cart);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create cart.',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }

    }

    // This method returns a single cart item for the authenticated user.
    public function show(Cart $cart)
    {
        try {
            $user = Auth::user();
            // Check if the cart item belongs to the authenticated user
            if($cart->user_id == $user->id) {
                // Return the cart item as a CartResource.
                return new CartResource($cart);
            } else {
                // Return a 404 error if the cart item does not belong to the authenticated user.
                return response()->json(['error' => 'Cart Item not found.'], 404);
            }
        } catch (ModelNotFoundException $e) {
            // Return a 404 error if the cart item does not exist.
            return response()->json(['error' => 'Cart Item not found.'], 404);
        }
    }

    // This method updates a single cart item for the authenticated user.
    public function update(CartRequest $request, Cart $cart)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            // Check if the cart item belongs to the authenticated user.
            if($cart->user_id == $user->id) {
                // Update the cart item with the validated request data.
                $cart->update($request->validated());
                $this->saveImage($request->image, 'carts', 300, 300);
                DB::commit();
                return new CartResource($cart);
            } else {
                return response()->json(['error' => 'Failed to update cart.'], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->all());

            DB::rollBack();
            return response()->json(['error' => 'Failed to update cart.'], 500);
        }
    }

    // This method deletes a single cart item for the authenticated user.
    public function destroy(Cart $cart)
    {
        try {
            $user = Auth::user();
            // Check if the cart item belongs to the authenticated user.
            if ($cart->user_id === $user->id) {
                $cart->delete();
                return response()->json(['message' => 'Cart item deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'You do not have permission to delete this cart item.'], 403);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to delete cart item.'], 500);
        }
    }

}
