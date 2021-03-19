<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_expense', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('finance_account_id');
            $table->unsignedBigInteger('expense_category_id');
            $table->bigInteger('amount');

            $table->foreign('finance_account_id')
                ->references('id')
                ->on('finance_accounts')
                ->onUpdate('cascade')
                ->onDelete('restrict ');
            $table->foreign('expense_category_id')
                ->references('id')
                ->on('expense_categories')
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
        Schema::dropIfExists('tr_expense');
    }
}
