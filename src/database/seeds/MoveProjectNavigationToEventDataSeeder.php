<?php

use Illuminate\Database\Seeder;

class MoveProjectNavigationToEventDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = DB::table('event_data')->count();
        if ($count == 0) {

            if (config('pano_edit.project') == 'vryun') {

                //移动定位
                $nav = DB::table('project_navigation')->where([['lat' ,'!=','']])->get();
                if (!empty($nav)) {


                    foreach ($nav as $key=>$value) {

                        //获取对应的按钮ID
                        $buttonRes = DB::table('buttons')->where(['foreign_keys_id'=>$value->project_id,'front_key'=>'navigation'])->first();
                        if ($buttonRes) {

                            $item = [];
                            $item['action_data']['province']   = $value->province;
                            $item['action_data']['city']       = $value->city;
                            $item['action_data']['district']   = $value->district;
                            $item['action_data']['address']    = $value->address;
                            $item['action_data']['lng']        = $value->lng;
                            $item['action_data']['lat']        = $value->lat;
                            $item['created_at']          = $value->created_at;
                            $item['updated_at']          = $value->updated_at;

                            $item['action_data']  = json_encode($item['action_data']);

                            $createRes = \Yjtec\PanoEdit\Model\EventData::create($item);
                            if ($createRes) {

                                DB::table('buttons')->where(['foreign_keys_id'=>$value->project_id,'front_key'=>'navigation'])->update(['event_id'=>$createRes->id]);
                            }
                        }
                        DB::table('project_action')->where('id',$value->id)->delete();
                        usleep(100);
                    }

                }
            }

            //移动迅游
            $cruise = DB::table('project_action')->where('action_id',1)->get();
            if ($cruise) {

                foreach ($cruise as $key=>$value) {

                    //获取对应的按钮ID
                    $buttonRes = DB::table('buttons')->where(['foreign_keys_id'=>$value->project_id,'front_key'=>'cruise'])->first();
                    if ($buttonRes) {

                        $item = [];
                        $item['action_data']  = $value->value;

                        $item['created_at']   = $value->created_at;
                        $item['updated_at']   = $value->updated_at;


                        $createRes = \Yjtec\PanoEdit\Model\EventData::create($item);
                        if ($createRes) {

                            DB::table('buttons')->where(['foreign_keys_id'=>$value->project_id,'front_key'=>'cruise'])->update(['event_id'=>$createRes->id]);
                        }
                    }

                    DB::table('project_action')->where('id',$value->id)->delete();
                    usleep(100);
                }
            }
        }
    }
}
