<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\TransactionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UploadProofRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionServiceInterface $transactionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $transactions = $this->transactionService->myTransactions(
            $request->user(),
            $request->integer('per_page', 15)
        );

        return $this->paginated(TransactionResource::collection($transactions));
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = $this->transactionService->create($request->user(), $request->validated());

        return $this->created(new TransactionResource($transaction));
    }

    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $transaction->load(['items.course', 'voucher', 'user']);

        return $this->success(new TransactionResource($transaction));
    }

    public function uploadProof(UploadProofRequest $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        $transaction = $this->transactionService->uploadPaymentProof(
            $transaction,
            $request->file('payment_proof')
        );

        return $this->success(new TransactionResource($transaction), 'Payment proof uploaded successfully');
    }
}
