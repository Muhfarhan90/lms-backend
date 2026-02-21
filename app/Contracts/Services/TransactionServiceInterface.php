<?php

namespace App\Contracts\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface TransactionServiceInterface
{
    public function create(User $user, array $data): Transaction;

    public function uploadPaymentProof(Transaction $transaction, UploadedFile $file): Transaction;

    public function verify(Transaction $transaction, User $admin): Transaction;

    public function myTransactions(User $user, int $perPage = 15): LengthAwarePaginator;
}
