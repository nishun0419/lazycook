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
        // $this->call(UsersTableSeeder::class);
    	for($i = 0; $i < 50; ++$i){
         	DB::table('Todos')->insert([
            	'title' => str_random(20),
            	'main' => str_random(50),
        	]);
        }
    }
}
