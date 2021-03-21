<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(IncomeCategoriesTableSeeder::class);
        $this->call(ExpenseCategoriesTableSeeder::class);
        $this->call(FinanceAccountsTableSeeder::class);
        $this->call(TransactionIncomeTableSeeder::class);
        $this->call(TransactionExpenseTableSeeder::class);
    }
}
