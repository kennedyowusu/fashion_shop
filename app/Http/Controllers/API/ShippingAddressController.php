<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingAddress;
use App\Http\Resources\ShippingResource;
use App\Models\ShippingAdress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $shippingAddress = ShippingAdress::where('user_id', $user->id)->get();
        return ShippingResource::collection($shippingAddress);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShippingAddress $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $user = Auth::user();

            $shippingAddress = new ShippingAdress($validatedData);
            $shippingAddress->user_id = $user->id;

            // Check if similar address already exists for the user
            if ($shippingAddress->getShippingAddress($user->id, $validatedData)) {
                return response()->json([
                    'error' => 'Shipping address already exists',
                ], 409);
            }
                    $shippingAddress->save();
            DB::commit();
            return new ShippingResource($shippingAddress);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Shipping Address not found',
                'message' => $th->getMessage(),
            ], 404);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Failed to create shipping address',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAdress $shippingAdress)
    {
        try {
            $user = Auth::user();
            if ($shippingAdress->user_id !== $user->id) {
                return response([
                    'error' => 'Unauthorized',
                    'message' => 'You are not authorized to view this resource',
                ], 404);
            } else {
                return new ShippingResource($shippingAdress);
            }
        } catch (\Throwable $th) {
            return abort(404, 'Shipping Address not found');
        }
    }

    public function showWithPhone(ShippingAdress $shippingAdress)
    {
        try {
            $user = Auth::user();
            if ($shippingAdress->user_id !== $user->id) {
                return response([
                    'error' => 'Unauthorized',
                    'message' => 'You are not authorized to view this resource',
                ], 404);
            } else {
                return new ShippingResource(
                    ShippingAdress::where('user_id', $user->id)
                        ->where('phone', $shippingAdress->phone)
                        ->get()
                );
            }
        } catch (\Throwable $th) {
            return abort(404, 'Shipping Address not found');
        }
    }

    public function showWithNameAndPhone(ShippingAdress $shippingAdress)
    {
        try {
            $user = Auth::user();
            if ($shippingAdress->user_id !== $user->id) {
                return response([
                    'error' => 'Unauthorized',
                    'message' => 'You are not authorized to view this resource.',
                ], 404);
            } else {
                return new ShippingResource(
                    ShippingAdress::where('user_id', $user->id)
                        ->where('phone', $shippingAdress->phone)
                        ->where('name', $shippingAdress->name)
                        ->get()
                );
            }
        } catch (\Throwable $th) {
            return abort(404, 'Shipping Address not found');
        }
    }

    public function showWithNameAndPhoneAndId(ShippingAdress $shippingAdress)
    {
        try {
            $user = Auth::user();
            if ($shippingAdress->user_id !== $user->id) {
                return response([
                    'error' => 'Unauthorized',
                    'message' => 'You are not authorized to view this resource',
                ], 404);
            } else {
                return new ShippingResource(
                    ShippingAdress::where('user_id', $user->id)
                        ->where('phone', $shippingAdress->phone)
                        ->where('name', $shippingAdress->name)
                        ->where('id', $shippingAdress->id)
                        ->get()
                );
            }
        } catch (\Throwable $th) {
            return abort(404, 'Shipping Address not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $validatedData = $request->validated();

            $shippingAddress = ShippingAddress::findOrFail($shippingAddress->id);

            if ($shippingAddress->user_id !== $user->id) {
                return response([
                    'error' => 'Unauthorized',
                    'message' => 'You are not authorized to view this resource',
                ], 404);
            }

            if ($shippingAddress->getShippingAddress($user->id, $validatedData)) {
                return response()->json([
                    'error' => 'Shipping address already exists',
                ], 409);
            }

            $shippingAddress->update($validatedData);

            DB::commit();
            return new ShippingResource($shippingAddress);
        } catch (\Throwable $th) {
            DB::rollBack();
            return abort(404, 'Shipping address not found');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'error' => 'Failed to update shipping address',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAdress $shippingAdress)
    {
        try {
            $user = Auth::user();
            if ($shippingAdress->user_id !== $user->id) {
                return response([
                    'error' => 'Unauthorized',
                    'message' => 'You are not authorized to view this resource',
                ], 404);
            } else {
                $shippingAdress->delete();
                return response([
                    'message' => 'Shipping Address deleted successfully',
                ], 200);
            }
        } catch (\Throwable $th) {
            return abort(404, 'Shipping Address not found');
        }
    }
}
