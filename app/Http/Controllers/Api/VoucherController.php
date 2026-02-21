<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\CheckVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    public function check(CheckVoucherRequest $request): JsonResponse
    {
        $voucher = Voucher::where('code', $request->input('code'))->first();

        if (! $voucher || ! $voucher->isValid()) {
            return $this->error('Voucher is invalid or has expired.', 422);
        }

        // Calculate discount based on the courses
        $courses   = \App\Models\Course::whereIn('id', $request->input('course_ids'))->get();
        $subtotal  = $courses->sum('price');
        $discount  = $voucher->calculateDiscount($subtotal);

        return $this->success([
            'voucher'  => new VoucherResource($voucher),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => max(0, $subtotal - $discount),
        ], 'Voucher is valid');
    }
}
