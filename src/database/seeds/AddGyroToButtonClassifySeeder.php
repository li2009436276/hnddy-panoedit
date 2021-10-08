<?php

use Illuminate\Database\Seeder;

class AddGyroToButtonClassifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = DB::table('button_classify')->where('id',18)->count();
        if ($count == 0) {
            DB::table('button_classify')->insert([
                [
                    'id' => 18,
                    'name' => 'gyro',
                    'container' => json_encode(['name' => 'gyro_container', 'keep' => 'true', 'type' => 'container', 'edge' => 'center']),
                    'icon' => json_encode(['name' => 'gyro_img', 'onclick' => 'gyro_start();']),
                    'text' => json_encode(['name' => 'gyro_text', 'onclick' => 'gyro_start();']),
                    'description' => '陀螺仪按钮',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
            ]);
        };
    }
}
