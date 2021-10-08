<?php

use Illuminate\Database\Seeder;

class AddPromptProjectActionClassifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = DB::table('project_action_classify')->whereIn('id',[2,3])->count();
        if ($count == 0) {

            DB::table('project_action_classify')->insert([
                    [
                        'id' => 2,
                        'name' => 'prompt',
                        'include_status' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 3,
                        'name' => 'page_cover',
                        'include_status' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ]
            );
        }
    }
}
