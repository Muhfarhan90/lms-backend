<?php

namespace App\Services;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Services\TransactionServiceInterface;
use App\Enums\TransactionStatus;
use App\Models\Course;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository
    ) {}

    public function create(User $user, array $data): Transaction
    {
        return DB::transaction(function () use ($user, $data) {
            $courses       = Course::whereIn('id', $data['course_ids'])->get();
            $subtotal      = $courses->sum('price');
            $discountTotal = 0;
            $voucher       = null;

            // Apply voucher if provided
            if (! empty($data['voucher_code'])) {
                $voucher = Voucher::where('code', $data['voucher_code'])->first();

                if (! $voucher || ! $voucher->isValid()) {
                    throw ValidationException::withMessages([
                        'voucher_code' => 'Voucher is invalid or has expired.',
                    ]);
                }

                $discountTotal = $voucher->calculateDiscount($subtotal);
            }

            $totalAmount = max(0, $subtotal - $discountTotal);

            /** @var Transaction $transaction */
            $transaction = $this->transactionRepository->create([
                'user_id'        => $user->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'subtotal'       => $subtotal,
                'discount_total' => $discountTotal,
                'total_amount'   => $totalAmount,
                'voucher_id'     => $voucher?->id,
                'status'         => TransactionStatus::Pending->value,
                'payment_method' => $data['payment_method'],
            ]);

            // Create transaction items
            foreach ($courses as $course) {
                $discountAmount = $courses->count() > 0 ? ($discountTotal / $courses->count()) : 0;

                TransactionItem::create([
                    'transaction_id'  => $transaction->id,
                    'course_id'       => $course->id,
                    'price'           => $course->price,
                    'discount_amount' => $discountAmount,
                    'final_price'     => max(0, $course->price - $discountAmount),
                ]);
            }

            // Record voucher usage
            if ($voucher) {
                VoucherUsage::create([
                    'voucher_id'     => $voucher->id,
                    'user_id'        => $user->id,
                    'transaction_id' => $transaction->id,
                    'used_at'        => now(),
                ]);
            }

            return $transaction->load(['items.course', 'voucher']);
        });
    }

    public function uploadPaymentProof(Transaction $transaction, UploadedFile $file): Transaction
    {
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $path = $file->store('transactions/proofs', 'public');

        return $this->transactionRepository->update($transaction, [
            'payment_proof' => $path,
            'paid_at'       => now(),
            'status'        => TransactionStatus::Paid->value,
        ]);
    }

    public function verify(Transaction $transaction, User $admin): Transaction
    {
        return $this->transactionRepository->update($transaction, [
            'status'      => TransactionStatus::Verified->value,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);
    }

    public function myTransactions(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->paginateByUser($user->id, $perPage);
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(Str::random(6));
    }
}
