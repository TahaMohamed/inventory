<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;

class PublicController extends Controller
{

    public function categories()
    {
        $categories = Category::select(['id', 'name'])->latest('id')->get();
        return ApiResponse::successResponse(CategoryResource::collection($categories));
    }
}
