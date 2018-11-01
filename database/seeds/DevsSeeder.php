<?php

use App\Dev;
use Illuminate\Database\Seeder;

class DevsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dev::create(['name' => 'dev1']);
        Dev::create(['name' => 'dev2']);
        Dev::create(['name' => 'dev3']);
    }
}
