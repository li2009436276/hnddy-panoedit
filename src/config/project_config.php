<?php

return [

    //巡游处理默认值
    'autorotate'                => [
        'auto'   => ['enabled'=>'true','loadscene'=>'true','waittime'=>'0.6','accel'=>'0.8','speed'=>'5.0','horizon'=>'0.0','tofov'=>'110'],
    ],

    //全景进入时的提示
    'start_pic_container'       => ['name'=>"startpic_container",'preload'=>"true",'handcursor'=>"true",'enabled'=>"true",'children'=>"true",'visible'=>"true",'zorder'=>"90",'type'=>"container",'maskchildren'=>"true",'keep'=>"true",'align'=>"center",'width'=>"200",'height'=>"200",'bgcolor'=>"0xFFFFFF",'bgalpha'=>"0"],
    'start_pic_icon'            => ['name'=>"skin_qidongtu",'zorder'=>"100",'width'=>"200",'height'=>"200",'keep'=>"true",'x'=>"0",'y'=>"0",'align'=>"center",'scale'=>"1",'enabled'=>"false",
        'onclick'=>'tween(layer[startpic_container].alpha,0,1);delayedcall(2,set(layer[startpic_container].enabled,false);set(layer[startpic_container].visible,false);stopdelayedcall(startpic1);stopdelayedcall(startpic2);'],

    //特效速度
    'effect_speed' => [
        ['flakes'=> 300,'speed'=> 2,'imagescale'=> 0.5,'shake'=> 1,'speedvariance'=> 5,'spreading'=> 2,'wind'=> 0.2],
        ['flakes'=> 600,'speed'=> 3,'imagescale'=> 0.5,'shake'=> 2,'speedvariance'=> 5,'spreading'=> 2,'wind'=> 0.2],
        ['flakes'=> 1500,'speed'=> 4,'imagescale'=> 0.5,'shake'=> 2,'speedvariance'=> 5,'spreading'=> 2,'wind'=> 0.2]
    ],

    //平面导航默认值
    'radar_container_default'   => ['name'=>'radar_container','keep'=>'true','type'=>'container','align'=>'righttop','x'=>-360,'y'=>80,'width'=>260,'height'=>260],
    'radar_bg_img_default'      => ['name'=>'radar_img','align'=>'right','x'=>0,'y'=>0,'scale'=>1,'width'=>260,'height'=>260,'handcursor'=>'false',],

];