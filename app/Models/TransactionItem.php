<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'course_id',
        'price',
        'discount_amount',
        'final_price',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price'     => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
