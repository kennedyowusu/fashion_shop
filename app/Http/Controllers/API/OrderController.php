<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection($this->order->all());
    }

    public function store(OrderRequest $request): OrderResource
    {
        DB::beginTransaction();

        try {
            $order = Order::create($request->validated());
            DB::commit();

            return new OrderResource($order);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->validated());

            DB::rollBack();
            return response()->json(['error' => 'Failed to create order.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Order $order)
    {
        try {
            return new OrderResource($order);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Order not found.'], 404);
        }
    }

    public function update(OrderRequest $request, Order $order): OrderResource
    {
        DB::beginTransaction();

        try {
            $order->update($request->validated());
            DB::commit();

            return new OrderResource($order);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($request->validated());

            DB::rollBack();
            return response()->json(['error' => 'Failed to update order.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Order $order): JsonResponse
    {
        DB::beginTransaction();

        try {
            $order->delete();
            DB::commit();

            return response()->json(['message' => 'Order deleted.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();
            return response()->json(['error' => 'Failed to delete order.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
