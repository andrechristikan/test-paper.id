<?php

use Illuminate\Database\Seeder;

class TransactionIncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_income')->insert([
            'id' => 1,
            'finance_account_id' => 1,
            'income_category_id' => 1,
            'amount' => 1000000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('transaction_income')->insert([
            'id' => 2,
            'finance_account_id' => 1,
            'income_category_id' => 2,
            'amount' => 500000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('transaction_income')->insert([
            'id' => 3,
            'finance_account_id' => 3,
            'income_category_id' => 1,
            'amount' => 200000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
