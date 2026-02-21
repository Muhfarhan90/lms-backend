<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     */
    protected function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        $response = ['message' => $message];

        if (! is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a created JSON response.
     */
    protected function created(mixed $data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * Return an error JSON response.
     */
    protected function error(string $message = 'An error occurred', int $status = 400, mixed $errors = null): JsonResponse
    {
        $response = ['message' => $message];

        if (! is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a 404 not found JSON response.
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Return a 403 forbidden JSON response.
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Return a 401 unauthorized JSON response.
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Return a paginated JSON response.
     */
    protected function paginated(AnonymousResourceCollection $resource, string $message = 'Success'): JsonResponse
    {
        $paginator = $resource->resource;

        $data = [
            'message' => $message,
            'data'    => $resource->toArray(request()),
            'meta'    => [
                'current_page' => $paginator instanceof LengthAwarePaginator ? $paginator->currentPage() : 1,
                'last_page'    => $paginator instanceof LengthAwarePaginator ? $paginator->lastPage() : 1,
                'per_page'     => $paginator instanceof LengthAwarePaginator ? $paginator->perPage() : $paginator->count(),
                'total'        => $paginator instanceof LengthAwarePaginator ? $paginator->total() : $paginator->count(),
            ],
        ];

        return response()->json($data, 200);
    }
}
