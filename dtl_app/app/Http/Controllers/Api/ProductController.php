<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Changelog;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductCreateEmailNotification;
use App\Providers\ChangelogEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Notification;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $product = Product::with(['type', 'creator'])->get();
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($product)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        /* product save */
        $product = Product::create($request->only(['name', 'status', 'created_by', 'product_type', 'price']));

        /* changelog data set and call event function*/
        $new_payload = [
            'name' => $product->name,
            'status' => $product->status,
            'created_by' => $product->created_by,
            'product_type' => $product->product_type,
            'price' => $product->price,
            'id' => $product->id,
            'created_at' => $product->created_at->format('m/d/Y g:i A'),
        ];
        $this->writeChangelog($action = 'create', $new_payload);

        # Proper way Because logged-in user always created user
//        $user = auth()->user(); # commented cz of no auth part written
        $user = User::find($request->get('created_by'));

        Notification::send($user, new ProductCreateEmailNotification($product));

        return response()->json([
            'success' => true,
            'message' => 'Product successfully created and a copy of the product has emailed.',
            'data' => new ProductResource($product)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['type', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Product successfully created and a copy of the product has emailed.',
            'data' => ProductResource::make($product)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(StoreProductRequest $request, Product $product): JsonResponse
    {
        /* get old values before update operations */
        $old_value = $product->getOriginal();
        $product->update($request->only(['name', 'status', 'created_by', 'product_type', 'price']));

        /* changelog data set and call event function */
        $new_payload = $product->getChanges();
        $old_payload = [
            'name' => $old_value['name'],
            'status' => $old_value['status'],
            'created_by' => $old_value['created_by'],
            'product_type' => $old_value['product_type'],
            'price' => $old_value['price'],
            'id' => $old_value['id'],
            'created_at' => $old_value['created_at']->format('m/d/Y g:i A'),
        ];
        $this->writeChangelog($action = 'update', $new_payload, $old_payload);

        return response()->json([
            'success' => true,
            'message' => 'Product successfully updated',
            'data' => new ProductResource($product)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        /* changelog data set and call event function */
        $new_payload = [
            'deleted_at' => $product['deleted_at']->format('m/d/Y g:i A'),
        ];
        $this->writeChangelog($action = 'delete', $new_payload);

        return response()->json('true', ResponseAlias::HTTP_OK);
    }

    /**
     * Search data can implement in index() method as well
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $product = Product::with(['type', 'creator'])
            ->whereHas('creator', function ($query) use ($request) {
                if (!empty($request->get('created_by'))) {
                    return $query->where('created_by', $request->get('created_by'));
                }
                return $query;
            })->when($request->has('name'), static function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->get('name') . '%');
            })
            ->whereHas('type', function ($query) use ($request) {
                if (!empty($request->get('type'))) {
                    return $query->where('product_type', $request->get('type'));
                }
                return $query;
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($product)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * get product logs
     *
     * @return JsonResponse
     */
    public function getLog(): JsonResponse
    {
        $log = Changelog::whereNotNull('action')->get();
        return response()->json([
            'success' => true,
            'data' => $log
        ], ResponseAlias::HTTP_OK);
    }


    /**
     * common method to call changelog.
     *
     * @param $action
     * @param array $new_payload
     * @param array $old_payload
     * @return void
     */
    private function writeChangelog($action, array $new_payload, array $old_payload = []): void
    {
        $log = [
            'action' => 'create',
            'new_payload' => $new_payload,
            'old_payload' => $old_payload
        ];
        event(new ChangelogEvent($log));
    }
}
