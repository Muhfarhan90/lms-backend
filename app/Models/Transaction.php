<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'subtotal',
        'discount_total',
        'total_amount',
        'status',
        'payment_method',
        'payment_proof',
        'voucher_id',
        'paid_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total_amount'   => 'decimal:2',
        'paid_at'        => 'datetime',
        'verified_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class);
    }
}
