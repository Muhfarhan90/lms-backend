<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function paginateByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findById(int $id, array $relations = []): ?\App\Models\Transaction;

    public function findByInvoice(string $invoiceNumber): ?\App\Models\Transaction;

    public function create(array $data): \App\Models\Transaction;

    public function update(\App\Models\Transaction $transaction, array $data): \App\Models\Transaction;
}
