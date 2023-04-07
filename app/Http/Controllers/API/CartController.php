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

    // A protected property $cart which is a reference to the Cart model.
    protected $cart;

    // In the constructor, the $cart property is set to an instance of the Cart model using dependency injection. This allows us to easily mock the Cart model when testing.
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Display a listing of the resource.
     *
     * The index() method returns a collection of all the cart items using a CartResource collection.
     */
    public function index()
    {
        return CartResource::collection($this->cart->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * The store() method handles creating a new cart item. It starts a database transaction,
     * validates the incoming data using the CartRequest form request, retrieves the currently authenticated user,
     * fetches the Product model associated with the product ID submitted in the request,
     * creates a new Cart instance with the validated data and sets the user_id and price attributes before saving to the database.
     * It then commits the transaction, s
     * aves the image (if any) and returns the newly created Cart resource as a CartResource.
     *
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
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create cart.',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * The show() method retrieves a single Cart item using its ID and returns it as a CartResource.
     * If the item is not found, it returns an error response.
     *
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
     *
     * The update() method handles updating an existing Cart item. It starts a database transaction,
     * validates the incoming data using the CartRequest form request,
     * and updates the Cart model instance with the validated data.
     * It then commits the transaction, saves the image (if any) and returns the updated Cart resource as a CartResource.
     * If an error occurs, it logs the error, rolls back the transaction and returns an error response.
     *
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
     *
     * The destroy() method handles deleting an existing Cart item.
     * It starts a database transaction, deletes the Cart model instance and commits the transaction.
     * If an error occurs, it logs the error, rolls back the transaction and returns an error response.
     * Otherwise, it returns a success message as a JSON response.
     *
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
