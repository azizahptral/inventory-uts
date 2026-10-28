
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->date('due_date')
                ->nullable()
                ->after('purchase_date');

            $table->decimal('paid_amount', 14, 2)
                ->default(0)
                ->after('total_amount');

            $table->date('paid_off_at')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'due_date',
                'paid_amount',
                'paid_off_at',
            ]);
        });
    }
};
