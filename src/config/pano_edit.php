<?php

return [
    'krpano_dir'    => env('KRPANO_DIR','/krpano'),
    'project'       => 'vryun',
    'route_prefix'  => 'h/api',                                  //路由前缀
    'ticket'        => 'home.auth',                              //ticket
    'admin'         => 'admin.auth',                              //管理员验证
    'auth'          => 'auth.api',                               //验证签名
    'ossutil_path'  => '/root/ossutil'							 //ossutil工具地址
];