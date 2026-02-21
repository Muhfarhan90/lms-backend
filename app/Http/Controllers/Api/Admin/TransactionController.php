<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Services\EnrollmentServiceInterface;
use App\Contracts\Services\TransactionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionServiceInterface    $transactionService,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly EnrollmentServiceInterface     $enrollmentService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters      = $request->only(['status', 'user_id']);
        $transactions = $this->transactionRepository->paginate($request->integer('per_page', 15), $filters);

        return $this->paginated(TransactionResource::collection($transactions));
    }

    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['user', 'items.course', 'voucher']);

        return $this->success(new TransactionResource($transaction));
    }

    public function verify(Request $request, Transaction $transaction): JsonResponse
    {
        $transaction = $this->transactionService->verify($transaction, $request->user());

        // Enroll user in each purchased course
        foreach ($transaction->items as $item) {
            try {
                $this->enrollmentService->enroll($transaction->user, $item->course_id);
            } catch (\Exception) {
                // Already enrolled — skip
            }
        }

        return $this->success(new TransactionResource($transaction->fresh(['user', 'items.course'])), 'Transaction verified and user enrolled');
    }
}
