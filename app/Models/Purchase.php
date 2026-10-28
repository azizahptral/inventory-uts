<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'purchase_number',
        'purchase_date',
        'total_amount',
        'status',
        'notes',
        'due_date',
        'paid_amount',
        'paid_off_at',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'due_date' => 'date',
            'paid_off_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->total_amount - (float) $this->paid_amount
        );
    }

    public function getPaymentStatusAttribute(): string
    {
        if ((float) $this->paid_amount <= 0) {
            return 'Belum Dibayar';
        }

        if ((float) $this->paid_amount < (float) $this->total_amount) {
            return 'Belum Lunas';
        }

        return 'Lunas';
    }
}
