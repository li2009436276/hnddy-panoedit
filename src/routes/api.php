<?php


Route::group([
    'prefix' => config('pano_edit.api_prefix')
], function () {


    Route::middleware('id.decode')->group(function () {
        Route::middleware(config('pano_edit.ticket'))->group(function () {

            //关于多边形的
            Route::get('/hotspotPolygons/add', 'HotspotPolygonsController@add')->name('hotspotPolynonsAdd');                                        //添加多边形热点
            Route::get('/hotspotPolygons/info', 'HotspotPolygonsController@info')->name('hotspotPolynonsInfo');                                       //获取多边形热点

            //关于3d模型
            Route::post('/model3d/addClassify', 'Model3dController@addClassify');                            //添加分类
            Route::get('/model3d/classifyLists', 'Model3dController@classifyLists');                         //分类列表
            Route::post('/model3d/editClassify', 'Model3dController@editClassify');                          //分类编辑
            Route::get('/model3d/delClassify', 'Model3dController@delClassify');                             //删除分类
            Route::get('/model3d/lists', 'Model3dController@lists');                                         //模型列表
            Route::post('/model3d/moveClassify', 'Model3dController@moveClassify');                          //移动分组
            Route::put('/model3d/update', 'Model3dController@update');                                       //更新3d模型
            Route::delete('/model3d/delete', 'Model3dController@delete');                                    //模型删除

            //后台管理
            Route::group([
                'prefix' => 'npc'

            ], function () {

                //环物
                Route::get('/ringsLists', 'RingsController@lists');                                             //用户环物图列表       （管理接口）
                Route::put('/rings/disable/{id}', 'RingsController@disable');                                   //禁用环物图       （管理接口）

            });


            //1.3.0 异步加载
            //项目评论
            Route::post('/project/addComment', 'ProjectCommentController@addComment');                                  //添加评论
            Route::get('/project/delComment', 'ProjectCommentController@delComment');                                   //删除评论
            Route::get('/project/getUserProjectCommentList', 'ProjectCommentController@getUserProjectCommentList');    //通过用户ID获取评论

        });

        //获取文章热点
        Route::get('/hotspot/hotspotArticle/{id}', 'HotspotController@hotspotArticle');

        //模型详情
        Route::get('/model3d/info', 'Model3dController@info');

        //1.3.0 异步加载
        Route::get('/project/commentLists', 'ProjectCommentController@commentLists');                              //获取评论列表

    });

    
    
    Route::middleware('id.decode')->group(function () {
        Route::middleware(config('pano_edit.admin'))->group(function () {
            
            Route::post('/fileUpload/uploadFile', 'FileUploadController@uploadFile');
        });
        
        Route::get('/xmlPlugin/plugin/{plugin}', 'XmlPluginController@plugin');
    });
});



