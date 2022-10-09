<?php

declare(strict_types=1);

use App\Models\Card;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Card::class)->constrained();
            $table->unsignedBigInteger('amount');
            $table->string('currency_code');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_transactions');
    }
}
