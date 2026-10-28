<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchasePaymentController extends Controller
{
    public function store(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => [
                'required',
                'in:cash,transfer,other',
            ],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use (
            $validated,
            $purchase
        ) {
            $lockedPurchase = Purchase::whereKey($purchase->id)
                ->lockForUpdate()
                ->firstOrFail();

            $remaining = (float) $lockedPurchase->total_amount
                - (float) $lockedPurchase->paid_amount;

            if ($remaining <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Tagihan ini sudah lunas.',
                ]);
            }

            if ((float) $validated['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'Pembayaran melebihi sisa utang.',
                ]);
            }

            PurchasePayment::create([
                'purchase_id' => $lockedPurchase->id,
                'user_id' => 1,
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' =>
                    $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $newPaidAmount = (float) $lockedPurchase->paid_amount
                + (float) $validated['amount'];

            $lockedPurchase->update([
                'paid_amount' => $newPaidAmount,
                'paid_off_at' => $newPaidAmount
                    >= (float) $lockedPurchase->total_amount
                    ? now()->toDateString()
                    : null,
            ]);
        });

        return redirect()
            ->route('purchases.show', $purchase)
            ->with('success', 'Pembayaran supplier berhasil dicatat.');
    }
}
