<?php

namespace Modules\Category\Http\Controllers;

use Modules\Category\Repositories\CategoryRepoEloquent;
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
                $categories,
            ],
            'status' => 'success',
        ]);
    }
}
