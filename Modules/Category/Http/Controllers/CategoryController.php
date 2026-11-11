<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Category\Http\Requests\SaveCategoryRequest;
use Modules\Category\Http\Resources\CategoryResource;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepoEloquent;
use Modules\Category\Services\CategoryService;
use Modules\Common\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepoEloquent $repo,
        private readonly CategoryService $service,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->repo->allFor($request->user()));
    }

    public function store(SaveCategoryRequest $request): CategoryResource
    {
        $category = $this->service->store($request->user(), $request->validated());

        return new CategoryResource($this->repo->loadCounts($category));
    }

    public function show(Category $category): CategoryResource
    {
        $this->authorize('view', $category);

        return new CategoryResource($this->repo->loadCounts($category));
    }

    public function update(SaveCategoryRequest $request, Category $category): CategoryResource
    {
        $this->authorize('update', $category);

        return new CategoryResource($this->repo->loadCounts($this->service->update($category, $request->validated())));
    }

    public function destroy(Category $category): Response
    {
        $this->authorize('delete', $category);

        $this->service->delete($category);

        return response()->noContent();
    }
}
