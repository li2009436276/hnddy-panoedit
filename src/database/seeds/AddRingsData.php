<?php

use Illuminate\Database\Seeder;
use App\Models\File;

class AddRingsData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = DB::table('rings')->where('id', 1)->count();
        if ($count == 0) {
            //查询出所有的环物图
            $files = File::where(['apply_type' => 2, 'status' => 1])->select(['id', 'user_id', 'path', 'ext'])->get();
            if (!empty($files)) {
                $createAt = date('Y-m-d H:i:s');
                foreach ($files as $k => $v) {

                    $path = substr($v->path['path'], 0, strrpos($v->path['path'], '/'));
                    $rings = DB::table('rings')->where('path', $path)->first();
                    if (empty($rings)) {
                        //环物不存在
                        $saveData = [
                            'user_id' => $v->user_id,
                            'path' => $path,
                            'thumb' => $path.'/1.'.$v->ext,
                            'num' => 1,
                            'created_at' => $createAt,
                        ];
                        DB::table('rings')->insert($saveData);
                    } else {
                        //环物已存在,增加数量
                        DB::table('rings')->where('id', $rings->id)->increment('num');
                    }
                }
            }

        }
    }
}
