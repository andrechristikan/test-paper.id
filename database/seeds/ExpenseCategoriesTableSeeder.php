<?php

use Illuminate\Database\Seeder;

class ExpenseCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expense_categories')->insert([
            'id' => 1,
            'name' => 'food',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('expense_categories')->insert([
            'id' => 2,
            'name' => 'transport',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);
    }
}
