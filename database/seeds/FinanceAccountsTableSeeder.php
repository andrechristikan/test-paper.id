<?php

use Illuminate\Database\Seeder;

class FinanceAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('finance_accounts')->insert([
            'id' => 1,
            'user_id' => 1,
            'name' => 'cash',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('finance_accounts')->insert([
            'id' => 2,
            'user_id' => 1,
            'name' => 'bank',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

    }
}
