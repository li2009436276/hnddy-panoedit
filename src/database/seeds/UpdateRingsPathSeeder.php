<?php

use Illuminate\Database\Seeder;
use Yjtec\PanoEdit\Model\Rings;

class UpdateRingsPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //查询出所有没有后缀的环物图
        $files = Rings::select(['id', 'path', 'thumb'])->where('ext', '')->withTrashed()->get();
        if (!empty($files)) {

            foreach ($files as $key => $value) {
                @$fileContent = file_get_contents($value->thumb['url']);
                if (!$fileContent) {
                    Rings::where(['id' => $value->id])->delete();
                    continue;
                }
                //如果最后一位是/
                $lastStr = substr($value->path,-1);
                $path = $lastStr == '/' ? $value->path : $value->path.'/';

                Rings::where(['id' => $value->id])->withTrashed()->update(
                    [
                        'path' => $path,
                        'ext' => substr($value->thumb['url'], strrpos($value->thumb['url'], '.')+1),
                        'deleted_at' => null
                    ]
                );
            }
        }
    }
}
