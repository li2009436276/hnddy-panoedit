<?php
use Illuminate\Database\Seeder;

class DelCruiseToEventDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = DB::table('project_action_classify')->where('name','cruise')->count();
        if ($count > 0) {

            DB::table('project_action_classify')->where('name','cruise')->delete();
        }
    }
}