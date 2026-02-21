<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Voucher\StoreVoucherRequest;
use App\Http\Requests\Admin\Voucher\UpdateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $vouchers = Voucher::latest()->paginate($request->integer('per_page', 15));

        return $this->paginated(VoucherResource::collection($vouchers));
    }

    public function show(Voucher $voucher): JsonResponse
    {
        return $this->success(new VoucherResource($voucher));
    }

    public function store(StoreVoucherRequest $request): JsonResponse
    {
        $voucher = Voucher::create($request->validated());

        return $this->created(new VoucherResource($voucher));
    }

    public function update(UpdateVoucherRequest $request, Voucher $voucher): JsonResponse
    {
        $voucher->update($request->validated());

        return $this->success(new VoucherResource($voucher->fresh()), 'Voucher updated successfully');
    }

    public function destroy(Voucher $voucher): JsonResponse
    {
        $voucher->delete();

        return $this->success(message: 'Voucher deleted successfully');
    }
}
