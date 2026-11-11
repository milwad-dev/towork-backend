<?php

namespace Modules\Label\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\Controller;
use Modules\Label\Http\Requests\SaveLabelRequest;
use Modules\Label\Http\Resources\LabelResource;
use Modules\Label\Models\Label;
use Modules\Label\Repositories\LabelRepoEloquent;
use Modules\Label\Services\LabelService;

class LabelController extends Controller
{
    public function __construct(
        private readonly LabelRepoEloquent $repo,
        private readonly LabelService $service,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return LabelResource::collection($this->repo->allFor($request->user()));
    }

    public function store(SaveLabelRequest $request): LabelResource
    {
        $label = $this->service->store($request->user(), $request->validated());

        return new LabelResource($this->repo->loadCounts($label));
    }

    public function show(Label $label): LabelResource
    {
        $this->authorize('view', $label);

        return new LabelResource($this->repo->loadCounts($label));
    }

    public function update(SaveLabelRequest $request, Label $label): LabelResource
    {
        $this->authorize('update', $label);

        return new LabelResource($this->repo->loadCounts($this->service->update($label, $request->validated())));
    }

    public function destroy(Label $label): Response
    {
        $this->authorize('delete', $label);

        $this->service->delete($label);

        return response()->noContent();
    }
}
