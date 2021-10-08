<?php

use Illuminate\Database\Seeder;

class ProjectActionClassifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = DB::table('project_action_classify')->whereIn('id',[1])->count();
        if ($count == 0) {

            DB::table('project_action_classify')->insert(
                [
                    'id' => 1,
                    'name' => 'open_animation',
                    'include_status' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        }
    }
}
