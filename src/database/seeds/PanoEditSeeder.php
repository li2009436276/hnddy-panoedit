<?php

use Illuminate\Database\Seeder;
class PanoEditSeeder extends Seeder
{
    public function run(){

        $this->call(ProjectActionClassifySeeder::class);
    }
}