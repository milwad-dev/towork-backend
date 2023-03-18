<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Category\Http\Requests\CategoryStoreRequest;
use Modules\Category\Http\Requests\CategoryUpdateRequest;
use Modules\Category\Repositories\CategoryRepoEloquent;
use Modules\Category\Services\CategoryService;
use Modules\Common\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = resolve(CategoryRepoEloquent::class)->getLatest(auth()->id())->get();

        return response()->json([
            'data' => [
                'categories' => $categories,
            ],
            'status' => 'success',
        ]);
    }

    /**
     * Store category by request.
     *
     * @param CategoryStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = resolve(CategoryService::class)->store($request->validated());

        return response()->json([
            'data' => [
                $category,
            ],
            'status' => 'success',
        ]);
    }

    /**
     * Update category by request.
     *
     * @param int                   $id
     * @param CategoryUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, int $id)
    {
        resolve(CategoryService::class)->update($id, $request->validated());

        return response()->json([
            'data' => [
                'message' => 'Category updated successfully.',
            ],
            'status' => 'success',
        ]);
    }

    public function destroy(int $id)
    {
        resolve(CategoryService::class)->delete($id);

        return response()->json([
            'data' => [
                'message' => 'Category deleted successfully.',
            ],
            'status' => 'success',
        ]);
    }
}
