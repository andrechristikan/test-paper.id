<?php

use Illuminate\Database\Seeder;

class IncomeCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('income_categories')->insert([
            'id' => 1,
            'name' => 'salary',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('income_categories')->insert([
            'id' => 2,
            'name' => 'allowance',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);
    }
}
