<?php

return [
    'krpano_dir'    => env('KRPANO_DIR','/krpano'),
    'project'       => 'vryun',
    'route_prefix'  => 'h/api',                                  //路由前缀
    'token'         => 'home.auth',                              //token
    'auth'          => 'auth.api',                               //验证签名
    'ossutil_path'  => '/root/ossutil'							 //ossutil工具地址
];