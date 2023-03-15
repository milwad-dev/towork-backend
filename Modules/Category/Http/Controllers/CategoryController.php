<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Category\Repositories\CategoryRepo;
use Modules\Common\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = resolve(CategoryRepo::class)->getLatest(auth()->id())->get();

        return response()->json([
            'data' => [
                $categories
            ],
            'status' => 'success'
        ]);
    }
}
