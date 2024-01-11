<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ItemRequest;
use App\Http\Resources\Api\ItemResource;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    public function __construct(protected ItemRepositoryInterface $itemRepositoryInterface)
    {
    }

    public function index()
    {
        $items = $this->itemRepositoryInterface->paginate(['id', 'name', 'price', 'category_id', 'created_at']);
        return ApiResponse::paginateResponse(ItemResource::collection($items), $items);
    }

    public function show(int $id)
    {
        return ApiResponse::successResponse(ItemResource::make($this->itemRepositoryInterface->show($id, ['category'])));
    }

    public function store(ItemRequest $request)
    {
        $item = $this->itemRepositoryInterface->store($request->validated() + ['supplier_id' => auth()->id()]);
        return ApiResponse::successResponse(ItemResource::make($item), __('Item created successfully'), Response::HTTP_CREATED);
    }

    public function update(ItemRequest $request, int $id)
    {
        $item = $this->itemRepositoryInterface->update($id, $request->validated());
        return ApiResponse::successResponse(ItemResource::make($item), __('Item updated successfully'));
    }

    public function destroy(int $id)
    {
        $item = $this->itemRepositoryInterface->destroy($id);
        return ApiResponse::successResponse(ItemResource::make($item), __('Item deleted successfully'));
    }
}
