<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker\Factory::create('ja_JP');
        for($i = 0; $i < 10; $i++) {
            App\Book::create([
                'user_id' => $faker->numberBetween(1,3), // 1〜3
                'item_name' => $faker->word(), //文字列
                'item_number' => $faker->numberBetween(1, 999), // 1〜999
                'item_amount' => $faker->numberBetween(100, 5000), // 100〜5000
                'published' => $faker->dateTime('now'), // 現在までYmdHis
                'created_at' => $faker->dateTime('now'), // 現在までYmdHis
                'updated_at' => $faker->dateTime('now'), // 現在までYmdHis
                'item_img' => '', // 空文字
            ]);
        }
    }
}
