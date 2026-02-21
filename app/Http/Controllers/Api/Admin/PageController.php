<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\StorePageRequest;
use App\Http\Requests\Admin\Page\UpdatePageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->success(PageResource::collection(Page::all()));
    }

    public function show(Page $page): JsonResponse
    {
        return $this->success(new PageResource($page));
    }

    public function store(StorePageRequest $request): JsonResponse
    {
        $page = Page::create($request->validated());

        return $this->created(new PageResource($page));
    }

    public function update(UpdatePageRequest $request, Page $page): JsonResponse
    {
        $page->update($request->validated());

        return $this->success(new PageResource($page->fresh()), 'Page updated successfully');
    }

    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return $this->success(message: 'Page deleted successfully');
    }
}
