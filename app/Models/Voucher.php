<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_discount',
        'usage_limit',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'expired_at'     => 'date',
        'discount_value' => 'decimal:2',
        'max_discount'   => 'decimal:2',
    ];

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Cek apakah voucher masih bisa digunakan
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->expired_at && $this->expired_at->isPast()) return false;
        if ($this->usage_limit !== null && $this->usages()->count() >= $this->usage_limit) return false;
        return true;
    }

    /**
     * Hitung nominal diskon dari harga
     */
    public function calculateDiscount(float $price): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = $price * ($this->discount_value / 100);
            return $this->max_discount ? min($discount, $this->max_discount) : $discount;
        }
        return min($this->discount_value, $price);
    }
}
