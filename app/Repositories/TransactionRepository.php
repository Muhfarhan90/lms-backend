<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function paginateByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Transaction::with(['items.course', 'voucher'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Transaction::with(['user', 'items.course', 'voucher']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id, array $relations = []): ?Transaction
    {
        return Transaction::with($relations)->find($id);
    }

    public function findByInvoice(string $invoiceNumber): ?Transaction
    {
        return Transaction::where('invoice_number', $invoiceNumber)->first();
    }

    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data);

        return $transaction->fresh();
    }
}
