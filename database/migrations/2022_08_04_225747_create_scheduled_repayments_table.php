<?php

declare(strict_types=1);

use App\Models\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_repayments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Loan::class)->constrained();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('outstanding_amount');
            $table->string('currency_code');
            $table->dateTime('due_date');
            $table->string('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_repayments');
    }
};
