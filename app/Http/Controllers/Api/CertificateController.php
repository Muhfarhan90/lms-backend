<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\CertificateServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct(
        private readonly CertificateServiceInterface $certificateService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $certificates = $this->certificateService->findByUser($request->user()->id);

        return $this->success(CertificateResource::collection($certificates));
    }

    public function show(Request $request, Certificate $certificate): JsonResponse
    {
        if ($certificate->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $certificate->load('course');

        return $this->success(new CertificateResource($certificate));
    }
}
