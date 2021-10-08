<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use App\Helper\ShieldHelper;
use App\Models\Panorama;
use App\Repositories\Eloquent\Repository;
use Yjtec\PanoEdit\Model\Project;
use App\Models\ProjectActivity\ProjectActivity;
use App\Models\Scene2;
use Yjtec\PanoEdit\Repositories\Contracts\ProjectInterface;
use App\Services\UploadService;
use DB;
use Yjtec\PanoEdit\Services\Embed\EmbedService;
use Yjtec\PanoEdit\Services\ProjectTemplate\ProjectTemplateService;

class ProjectRepository extends Repository implements ProjectInterface
{

    public function model()
    {
        return Project::class;
    }

    /**
     * 创建项目
     * @param $data
     * @return mixed|void
     */
    public function create($data)
    {
        $res = $this->model->create($data);
        return $res;
    }

    /**
     * 项目添加
     * @param $data
     * @return mixed
     */
    public function add($data,$panoramas)
    {
        DB::beginTransaction();

        $sceneData = Panorama::whereIn('id',$panoramas)
            ->select(['id','name','filename','path','file_path','scene_image','scene_preview','thumb','domain','status','service_pano_id'])
            ->get()
            ->toArray();

        if (empty($sceneData)) {

            tne('PROJECT_SCENE_EMPTY');
        }

        //保存第一个场景的缩略图
        if (!empty($sceneData[0]['thumb']['path'])) {

            $data['thumb']  = $sceneData[0]['thumb']['path'];
        }

        $data['status'] = 4;
        foreach ($sceneData as $key=>$value) {

            if ($value['status'] == 1 || $value['status'] == 2) {

                $data['status'] = $value['status'];
                break;
            }
        }

        $res = Project::create($data);

        if ($res) {

            $index = 1;
            foreach ($sceneData as $key=>&$value) {

                $filePath                   = $value['file_path'];
                $thumb                      = $value['thumb'];
                $panorama_id                = $value['id'];
                $scene_preview              = $value['scene_preview'];

                unset($value['file_path']);
                unset($value['thumb']);
                unset($value['id']);

                $value['file_path']         = $filePath['path'];
                $value['thumb']             = $thumb['path'];
                $value['project_id']        = $res->id;
                $value['panorama_id']       = $panorama_id;
                $value['scene_preview']     = $scene_preview['path'];
                $value['created_at']        = date('Y-m-d H:i:s');
                $value['updated_at']        = date('Y-m-d H:i:s');
                $value['key']               = $index;
                $index++;
            }

            $sceneRes = Scene2::insert($sceneData);

            if ($sceneRes) {

                DB::commit();
                return $res;
            }
        }

        DB::rollback();
        return false;

    }

    /**
     * 项目列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @param $orderFields
     * @return mixed
     */
    public function lists($where, $pageSize = 10, $field = '*',$orderFields = NULL)
    {
        //屏蔽项目
        $shieldProject = new ShieldHelper();
        $shieldLists   = $shieldProject->getShieldProject();

        //获取屏蔽用户
        $shieldUserLists = app()->make('request')->shield_user ? app()->make('request')->shield_user : '';

        $object = Project::where($where)
            ->with('classify')
            ->select($field);

        //屏蔽用户
        if (!empty($shieldUserLists)) {

            $object->whereNotIn('user_id',explode(',',$shieldUserLists));
        }

        //屏蔽项目
        if ($shieldLists) {

            $object->whereNotIn('id',$shieldLists);
        }

        //进行排序
        if ($orderFields) {

            foreach ($orderFields as $key=>$value) {

                $object->orderBy($key,$value);
            }
        }

        $res = $object->orderBy('created_at','desc')
            ->paginate($pageSize);

        return $res;
    }



    /**
     * 项目列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @param $orderFields
     * @return mixed
     */
    public function getLists($user_id, $field = '*',$orderFields = NULL)
    {

        $where['status']            = 4;
        $where['public_status']     = 1;

        $res = array();
        foreach($user_id as $v){
            $where['user_id']       = $v;
            $res[$v] = Project::where($where)
                ->with('classify')
                ->select($field)
                ->orderBy('created_at','desc')
                ->limit(3)
                ->get();
        }

        return $res;
    }

    /**
     * 项目列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @param $orderFields
     * @return mixed
     */
    public function userProjectLists($where, $pageSize = 10, $field = '*')
    {

        $res = Project::where($where)
            ->with('classify')
            ->select($field)
            ->orderBy('created_at','desc')
            ->paginate($pageSize);

        return $res;
    }

    /**
     * 增加字段值
     * @param $where
     * @param $field
     * @param $num
     * @return mixed
     */
    public function increment($where,$field,$num){
        $res = Project::where($where)->increment($field,$num);
        return $res;
    }

    /**
     * 单独获取项目详情
     * @param $where
     * @param $field
     * @return mixed|void
     */
    public function getInfo($where, $field = '*')
    {

        $res = Project::where($where)
            ->select($field)
            ->first();
        return $res;
    }

    /**
     * 单独获取项目详情
     * @param $where
     * @param $field
     * @return mixed|void
     */
    public function getInfoMultiple($where, $field = '*')
    {
        $user_id = $where['user_id'];
        unset($where['user_id']);
//        DB::connection()->enableQueryLog();
        $res = Project::where($where)
            ->whereIn('user_id',$user_id)
            ->select($field)
            ->groupBy('user_id')
            ->get();
//        var_dump(DB::getQueryLog());
//        exit;
        return $res;
    }


    /**
     * 获取项目信息
     * @param $where
     * @param string $field
     * @return mixed|void
     */
    public function info($where, $field = '*')
    {
        $res = Project::where($where)
            ->with('buttons','category','scenes','radar_container','project_navigation','project_action')
            ->select($field)
            ->first()
            ->toArray();

        //获取项目模板信息
        $ptService = new ProjectTemplateService();
        $res = $ptService->mergeTemplateInfo($res);

        //将项目信息存入info字段
        $res['info']            = ['id'=>$res['id'],'name'=>$res['name'],'thumb'=>$res['thumb'],'labels'=>!empty(explode(',',$res['labels'])[0]) ? explode(',',$res['labels']) : [],'description'=>htmlspecialchars_decode($res['description']),'classify_id'=>$res['classify_id'],'good'=>$res['good'],'view'=>$res['view'],'public_status'=>$res['public_status']];
        unset($res['name'],$res['thumb'],$res['labels'],$res['description'],$res['classify_id'],$res['good'],$res['view'],$res['public_status']);

        //项目导航信息
        $res['info']['location'] = !empty($res['project_navigation']) ? $res['project_navigation']  : new \stdClass();

        //项目功能列表
        if (!empty($res['project_action'])) {

            $res['project_action'] = [];

            foreach ($res['project_action'] as $key=>$value){

                $res['project_action'][$value['action_classify']['name']] = json_decode($value['value'],true);
            }

            unset($res['project_action']);
        }

        //项目button
        if (!empty($res['buttons'])) {

            foreach ($res['buttons'] as $key=>&$value) {

                $value['container']        = json_decode($value['container'],true);
                $value['icon']             = json_decode($value['icon'],true);
                $value['text']             = json_decode($value['text'],true);
                $value['id']               = $value['front_key'];

                //删除不需要的字段
                unset($value['classify'],$value['classify_id'],$value['foreign_keys_id'],$value['front_key']);
                unset($value['icon']['onclick'],$value['text']['onclick']);
            }
        }

        //处理平面导航
        if (!empty($res['radar_container'])) {

            $res[$res['radar_container']['front_key']]['container']         = json_decode($res['radar_container']['container'],true);
            $res[$res['radar_container']['front_key']]['bg_img']            = json_decode($res['radar_container']['bg_img'],true);
            $res[$res['radar_container']['front_key']]['spots']             = $res['radar_container']['radar'];
            unset($res['radar_container']);
        }

        foreach ($res['scenes'] as $key=>&$value) {

            //场景image
            $value['scene_image'] = json_decode($value['scene_image'],true);
            unset($value['key']);

            //处理热点
            if (!empty($value['hotspot'])) {

                foreach ($value['hotspot'] as $k=>&$v) {

                    $v['action']            = intval($v['action']);
                    $v['actionData']        = json_decode($v['action_data'],true);
                    $v['img']               = json_decode($v['img'],true);
                    $v['iconType']          = $v['icon_type'];
                    $v['titleIsShow']       = $v['show_title'] ? true : false;

                    unset($v['scene_id'],$v['action_data'],$v['icon_type'],$v['show_title']);
                }
            }

            //多边形热点
            if (!empty($value['hotspot_polygons'])) {

                foreach ($value['hotspot_polygons'] as $k=>&$v){

                    $v['action']            = intval($v['action']);
                    $v['actionData']        = json_decode($v['action_data'],true);
                    $v['img']               = json_decode($v['img'],true);
                    $v['img']['point']      = $v['point'];
                    $v['iconType']          = $v['icon_type'];
                    $v['titleIsShow']       = $v['show_title'] ? true : false;

                    unset($v['scene_id'],$v['action_data'],$v['icon_type'],$v['show_title'],$v['point']);
                }

            }

            //将多变性热点放到热点内
            $value['hotspot'] = array_merge($value['hotspot'],$value['hotspot_polygons']);
            unset($value['hotspot_polygons']);

            //场景特效
            if (!empty($value['effect'])) {

                $effect = json_decode($value['effect']['value'],true);
                unset($value['effect']);
                $value['effect'] = $effect;

            }

            //音乐处理
            if (!empty($value['music'])) {

                foreach ($value['music'] as $j=>$l) {
                    $value['music'][$l['key'].'']  = json_decode($l['value'],true);
                    unset($value['music'][$j]);
                }
            }

            //遮罩处理
            if (!empty($value['mask'])) {

                foreach ($value['mask'] as $j=>$l) {

                    if ($l['type'] == 12) {

                        $value['mask']['skyUrl'] = $l['url'];

                    } else {

                        $value['mask']['groundUrl'] = $l['url'];
                    }
                    unset($value['mask'][$j]);
                }
            }

            //处理嵌入
            if (!empty($value['embed'])) {

                $embedService = new EmbedService($value['embed'],$value['id']);
                $embedService->editData();
                $embedData = $embedService->getEditData();
                $value['embed'] = $embedData;
            }

        }

        return $res;
    }

    /**
     * 生成xml用
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function getInfoForXml($where, $field = '*')
    {
        $res = Project::where($where)
            ->with('buttons','category','scenes','radar_container','project_navigation','project_action')
            ->select($field)
            ->first()
            ->toArray();

        $res['description'] = htmlspecialchars_decode($res['description']);

        $res['info'] = [
            'location'      => !empty($res['project_navigation']) ? $res['project_navigation']  : null,
            'name'          => $res['name'],
            'description'   => $res['description'],
            'created_at'    => $res['created_at'],
        ];

        //项目功能列表
        if (!empty($res['project_action'])) {

            foreach ($res['project_action'] as $key=>$value){

                $res['project_action'][$value['action_classify']['name']] = json_decode($value['value'],true);
            }

        }

        //项目button
        if (!empty($res['buttons'])) {

            foreach ($res['buttons'] as $key=>&$value) {

                $value['container']                 = json_decode($value['container'],true);
                $value['icon']                      = json_decode($value['icon'],true);
                $value['text']                      = json_decode($value['text'],true);

                //处理默认值
                if (!empty($value['classify'])) {

                    $value['classify']['container'] = json_decode($value['classify']['container'],true);
                    $value['classify']['icon']      = json_decode($value['classify']['icon'],true);
                    $value['classify']['text']      = json_decode($value['classify']['text'],true);
                }

            }
        }


        //处理平面导航
        if (!empty($res['radar_container'])) {

            $res[$res['radar_container']['front_key']]['container']         = json_decode($res['radar_container']['container'],true);
            $res[$res['radar_container']['front_key']]['bg_img']            = json_decode($res['radar_container']['bg_img'],true);
            $res[$res['radar_container']['front_key']]['spots']             = $res['radar_container']['radar'];
            unset($res['radar_container']);
        }

        foreach ($res['scenes'] as $key=>&$value) {

            //场景image
            $value['scene_image'] = json_decode($value['scene_image'],true);


            //处理热点
            if (!empty($value['hotspot'])) {

                foreach ($value['hotspot'] as $k=>&$v) {

                    unset($v['scene_id'],$v['show_title']);
                    $v['action']        = intval($v['action']);
                    $v['action_data']   = json_decode($v['action_data'],true);
                    $v['img']           = json_decode($v['img'],true);
                }
            }

            //场景特效
            if (!empty($value['effect'])) {

                $effect = json_decode($value['effect']['value'],true);
                unset($value['effect']);
                $value['effect'] = $effect;

            }

            //音乐处理
            if (!empty($value['music'])) {

                foreach ($value['music'] as $j=>$l) {
                    $value['music'][$l['key'].'']  = json_decode($l['value'],true);
                    unset($value['music'][$j]);
                }
            }

        }

        return $res;
    }

    /**
     * 项目编辑
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data)
    {
        $res = Project::where($where)->update($data);
        return $res;
    }

    /**
     * 移动分组
     * @param $ids
     * @param $groupId
     * @return mixed
     */
    public function moveGroup($ids, $groupId)
    {
        $res = Project::whereIn('id',$ids)->update(['group_id'=>$groupId]);
        return $res;
    }

    /**
     * 项目删除
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        DB::beginTransaction();
        $res = Project::where($where)->delete();

        if (time()<env('PROJECT_ACTIVITY_END_TIME') && $res) {

            $activityRes = ProjectActivity::where(['project_id'=>$where['id']])->delete();
            if ($activityRes === false) {

                DB::rollback();
                return false;
            }
        }

        if ($res) {

            DB::commit();
            return true;
        }
        DB::rollback();
        return false;
    }

    /**
     * 回收站
     * @param $where
     * @param int $pageSize
     * @param string $field
     * @return mixed
     */
    public function projectRecycle($where, $pageSize = 10, $field = '*')
    {
        $res = Project::onlyTrashed()->where($where)
            ->orderBy('created_at','desc')
            ->select($field)
            ->paginate($pageSize);
        return $res;
    }

    /**
     * whereIn查询
     * @param $searchField
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function whereInCount($searchField,$where,$field = '*'){

        $res = Project::whereIn($searchField,$where)->select('*')->count();
        return $res;
    }

    /**
     * 获取project统计数据
     * @return mixed
     */
    public function getStatisticsData()
    {
        $total = Project::select('*')->count();
        $public = Project::select('*')->where('public_status','=','1')->count();
        $private = ($total - $public) > -1 ? ($total - $public) : 0;

        $today = Project::select('*')->whereRaw('DateDiff(now(),created_at)=?',[0])->get(); //本日新增

        $public_new = $private_new = 0;
        foreach ($today as $v){
            if($v->public_status == 1){
                $public_new++;
            }else{
                $private_new++;
            }
        }

        return [
            'total' => $total,
            'public' => $public,
            'private' => $private,
            'public_new' => $public_new,
            'private_new' => $private_new,
        ];
    }

    /**
     * 获取project统计最近2月（8周）的历史数据
     * @return mixed
     */
    public function getStatisticsHistoryData()
    {
        // 计算最近8周每周的起止时间
        date_default_timezone_set("Asia/Shanghai");
        $week8Start = $this->calcData(7);
        $week8end = $this->calcData(7,false);
        $week7Start = $this->calcData(6);
        $week7end = $this->calcData(6,false);
        $week6Start = $this->calcData(5);
        $week6end = $this->calcData(5,false);
        $week5Start = $this->calcData(4);
        $week5end = $this->calcData(4,false);

        $week4Start = $this->calcData(3);
        $week4end = $this->calcData(3,false);
        $week3Start = $this->calcData(2);
        $week3end = $this->calcData(2,false);
        $week2Start = $this->calcData(1);
        $week2end = $this->calcData(1,false);
        $week1Start = $this->calcData(0);
        $week1end = $this->calcData(0,false);

        $arrPublic = ['eight' => 0, 'seven' => 0, 'six' => 0, 'five' => 0, 'four' => 0, 'three' => 0, 'two' => 0, 'one' => 0];
        $arrPrivate = ['eight' => 0, 'seven' => 0, 'six' => 0, 'five' => 0, 'four' => 0, 'three' => 0, 'two' => 0, 'one' => 0];

        $data = Project::select('id','public_status','created_at')
            ->where([
                ['created_at','>',$week8Start],
                ['deleted_at','=',null], //删除的不用统计！
            ])
            ->get();

        foreach ($data as $v ) {
            if(($v->created_at > $week8Start) && ($v->created_at < $week8end) ){
                if($v->public_status == 1){
                    $arrPublic['eight'] += 1;
                }else{
                    $arrPrivate['eight'] += 1;
                }
            }else if(($v->created_at > $week7Start) && ($v->created_at < $week7end) ){
                if($v->public_status == 1){
                    $arrPublic['seven'] += 1;
                }else{
                    $arrPrivate['seven'] += 1;
                }
            }else if(($v->created_at > $week6Start) && ($v->created_at < $week6end) ){
                if($v->public_status == 1){
                    $arrPublic['six'] += 1;
                }else{
                    $arrPrivate['six'] += 1;
                }
            }else if(($v->created_at > $week5Start) && ($v->created_at < $week5end) ){
                if($v->public_status == 1){
                    $arrPublic['five'] += 1;
                }else{
                    $arrPrivate['five'] += 1;
                }
            }else if(($v->created_at > $week4Start) && ($v->created_at < $week4end) ){
                if($v->public_status == 1){
                    $arrPublic['four'] += 1;
                }else{
                    $arrPrivate['four'] += 1;
                }
            }else if(($v->created_at > $week3Start) && ($v->created_at < $week3end) ){
                if($v->public_status == 1){
                    $arrPublic['three'] += 1;
                }else{
                    $arrPrivate['three'] += 1;
                }
            }else if(($v->created_at > $week2Start) && ($v->created_at < $week2end) ){
                if($v->public_status == 1){
                    $arrPublic['two'] += 1;
                }else{
                    $arrPrivate['two'] += 1;
                }
            }else if(($v->created_at > $week1Start) && ($v->created_at < $week1end) ){
                if($v->public_status == 1){
                    $arrPublic['one'] += 1;
                }else{
                    $arrPrivate['one'] += 1;
                }
            }
        }

        $k1 = date("Y年m月d日",strtotime($week8Start));
        $k2 = date("Y年m月d日",strtotime($week7Start));
        $k3 = date("Y年m月d日",strtotime($week6Start));
        $k4 = date("Y年m月d日",strtotime($week5Start));
        $k5 = date("Y年m月d日",strtotime($week4Start));
        $k6 = date("Y年m月d日",strtotime($week3Start));
        $k7 = date("Y年m月d日",strtotime($week2Start));
        $k8 = date("Y年m月d日",strtotime($week1Start));

        $res = [
            'fields' => [$k1, $k2, $k3, $k4, $k5, $k6, $k7, $k8],
            'public' => [
                'name' => '公开项目',
                $k1 => $arrPublic['eight'],
                $k2 => $arrPublic['seven'],
                $k3 => $arrPublic['six'],
                $k4 => $arrPublic['five'],
                $k5 => $arrPublic['four'],
                $k6 => $arrPublic['three'],
                $k7 => $arrPublic['two'],
                $k8 => $arrPublic['one'],
            ],
            'private' => [
                'name' => '私有项目',
                $k1 => $arrPrivate['eight'],
                $k2 => $arrPrivate['seven'],
                $k3 => $arrPrivate['six'],
                $k4 => $arrPrivate['five'],
                $k5 => $arrPrivate['four'],
                $k6 => $arrPrivate['three'],
                $k7 => $arrPrivate['two'],
                $k8 => $arrPrivate['one'],
            ],
        ];

        return $res;
    }

    private function calcData($n,$isStart = true){
        if($isStart){
            return date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1-7*$n,date("Y")));
        }else{
            return date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7*$n,date("Y")));
        }
    }


    /**
     * 返回项目信息，供应用模板
     * @param $where
     * @return mixed
     */
    public function projectForUseTemplate($where)
    {
        $res = $this->model
            ->with('buttons')
            ->where($where)
            ->first();
        return $res;
    }

    /**
     * xml地址
     * @param $data
     * @return string
     */
    private function getXmlPath($data){

        $createdAt = strtotime($data['created_at']);
        $filepath = 'xml_cache/'.date('Y',$createdAt).'/'.date('m',$createdAt).'/'.date('d',$createdAt).'/'.$data['id'].'.xml';
        return $filepath;
    }

    /**
     * 删除xml
     * @param $id
     * @return mixed|void
     */
    public function deleteXml($id){

        $info = $this->getInfo(['id'=>$id],['created_at','id']);
        $filepath = $this->getXmlPath($info);
        if (\Storage::disk('public')->exists($filepath)) {

            \Storage::disk('public')->delete($filepath);
        }
    }
}