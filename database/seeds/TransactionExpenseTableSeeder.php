<?php

use Illuminate\Database\Seeder;

class TransactionExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_expense')->insert([
            'id' => 1,
            'finance_account_id' => 1,
            'expense_category_id' => 1,
            'amount' => 50000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('transaction_expense')->insert([
            'id' => 2,
            'finance_account_id' => 1,
            'expense_category_id' => 1,
            'amount' => 30000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('transaction_expense')->insert([
            'id' => 3,
            'finance_account_id' => 1,
            'expense_category_id' => 1,
            'amount' => 20000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('transaction_expense')->insert([
            'id' => 4,
            'finance_account_id' => 1,
            'expense_category_id' => 2,
            'amount' => 100000,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);
    }
}
