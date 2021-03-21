<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_income', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('finance_account_id');
            $table->unsignedBigInteger('income_category_id');
            $table->bigInteger('amount');
            
            $table->foreign('finance_account_id')
                ->references('id')
                ->on('finance_accounts')
                ->onUpdate('cascade')
                ->onDelete('restrict ');
            $table->foreign('income_category_id')
                ->references('id')
                ->on('income_categories')
                ->onUpdate('cascade')
                ->onDelete('restrict ');

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
        Schema::dropIfExists('transaction_income');
    }
}
