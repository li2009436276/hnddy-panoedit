<?php

use Illuminate\Database\Seeder;

class AddSwitchSceneToProjectActionClassifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = DB::table('project_action_classify')->whereIn('id',[4,5])->count();
        if ($count == 0) {

            DB::table('project_action_classify')->insert(
                [
                    [
                        'id' => 4,
                        'name' => 'loadscene_action',
                        'include_status' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                ]
            );
        }
    }
}
