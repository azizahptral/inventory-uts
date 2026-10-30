<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'code')) {
                    $table->string('code')->nullable()->after('name');
                }
                if (!Schema::hasColumn('categories', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
            });
        }

        if (!Schema::hasColumn('products', 'category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('supplier_id')
                    ->constrained('categories')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropConstrainedForeignId('category_id');
            });
        }

        Schema::dropIfExists('categories');
    }
};
