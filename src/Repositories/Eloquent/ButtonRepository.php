<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\Button;
use Yjtec\PanoEdit\Model\ButtonClassify;
use Yjtec\PanoEdit\Repositories\Contracts\ButtonInterface;
use Yjtec\Repo\Repository;

class ButtonRepository extends Repository implements ButtonInterface
{

    public function model()
    {
        return Button::class;
    }

    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $res = Button::create($data);
        return $res;
    }

    /**
     * 列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*')
    {
        $res = $this->model
            ->where($where)
            ->with('event_data')
            ->select($field)
            ->get();
        return $res;
    }

    /**
     * 编辑
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data)
    {
        $res = Button::where($where)->update($data);
        return $res;
    }

    /**
     * 删除
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $res = Button::where($where)->delete();
        return $res;
    }

    /**
     * 编辑或新增
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where, $data)
    {
        $res = Button::updateOrCreate($where,$data);
        return $res;
    }

    /**
     * 保存编辑
     * @param $foreignKeysId
     * @param $type
     * @param $data
     * @param $eventData
     * @return mixed
     */
    public function saveEditButton($foreignKeysId,$data,$eventData, $type = 1)
    {

        //按钮进行排序
        $updateRes = $this->sortButton($foreignKeysId);
        if (!$updateRes) {

            return false;
        }

        //更新事件
        $eventRepo = resolve('Yjtec\PanoEdit\Repositories\Contracts\EventDataInterface');

        $buttonFront = [];
        foreach ($data as $key=>$value) {
            
            if(in_array($value['id'], $buttonFront)){
                continue;
            }

            //处理点击 //过段时间删除
            if (!empty($value['icon']['onclick'])) {

                $value['icon']['onclick'] = '';
            }

            if (!empty($value['text']['onclick'])) {

                $value['text']['onclick'] = '';
            }

            if ($value['id'] != 'musicBg') {

                if (!empty($value['text']['ondown']) && $value['text']['ondown'] == 'bgSound_change();') {

                    unset($value['text']['ondown']);
                }


                if (!empty($value['icon']['ondown']) && $value['icon']['ondown'] == 'bgSound_change();') {

                    unset($value['icon']['ondown']);
                }

            }

            //处理button类型
            $buttonClassify = ButtonClassify::where(['name'=>$value['id']])->select(['id','icon','text'])->first();
            if ($buttonClassify) {

                $value['classify_id']       = $buttonClassify['id'];

                $buttonText                 = json_decode($buttonClassify['text'],true);
                $buttonIcon                 = json_decode($buttonClassify['icon'],true);
                if (!empty($buttonIcon['onclick'])) {

                    $value['icon']['onclick']   = $buttonIcon['onclick'];
                    $value['text']['onclick']   = $buttonText['onclick'];
                }
            }

            //判断图标是否需要剪切
            if (in_array($value['id'],config('config.crop_button_array')) && !empty($value['icon']['url'])) {

                $res = getImgWhComposer(\Storage::url(env('APP_ENV').$value['icon']['url']),$value['icon']['url']);
                $value['icon']['crop'] = '0|0|'.$res[0].'|'.$res[0];

                if ($value['id'] == 'cruise') {

                    if (!empty($value['icon']['onclick'])) {

                        $value['icon']['onclick'] .= 'switch(crop,0|0|'.$res[0].'|'.$res[0].',0|'.$res[0].'|'.$res[0].'|'.$res[0].');';
                    } else {

                        $value['icon']['onclick'] = 'switch(crop,0|0|'.$res[0].'|'.$res[0].',0|'.$res[0].'|'.$res[0].'|'.$res[0].');';
                    }
                }
            }

            //处理打电话
            if ($value['id'] == 'tel' && !empty($value['container']['phone'])) {

                $value['icon']['onclick']   = 'openurl(tel:'.$value['container']['phone'].');';
                $value['text']['onclick']   = 'openurl(tel:'.$value['container']['phone'].');';
            }

            //处理外链
            if ($value['id'] == 'link') {

                if (!empty($value['container']['link'])) {

                    $value['icon']['onclick']   = 'openurl('.$value['container']['link'].','.$value['container']['openMode'].');';
                    $value['text']['onclick']   = 'openurl('.$value['container']['link'].','.$value['container']['openMode'].');';
                } else {

                    $value['icon']['onclick']   = '';
                    $value['text']['onclick']   = '';
                }

            }

            $value['container']             = json_encode($value['container']);
            $value['icon']                  = json_encode($value['icon']);
            $value['css']                   = json_encode($value['text']['css']);
            $value['text']                  = json_encode($value['text']);
            $value['front_key']             = $value['id'];
            $value['foreign_keys_id']       = $foreignKeysId;
            $value['key']                   = $key + 1;

            $where['foreign_keys_id']       = $foreignKeysId;
            $where['key']                   = $value['key'];
            $where['type']                  = $type;

            unset($value['id']);

            //查询当前按钮
            $buttonRes = $this->model->where(['foreign_keys_id' => $foreignKeysId,'front_key' => $value['front_key']])->first();
            if ($buttonRes) {

                if (!empty($buttonRes->event_id)) {

                    $value['event_id'] = $buttonRes->event_id;
                }
            }

            //更新事件
            if (!empty($eventData[$value['front_key']])) {

                $eventRes = $eventRepo->updateOrCreate(!empty($buttonRes->event_id) ? $buttonRes->event_id : 0,['action_data'=>json_encode($eventData[$value['front_key']])]);
                if ($eventRes === false) {

                    return false;
                }

                $value['event_id'] = $eventRes->id;
            }

            if ($buttonRes) {

                $res = $this->update($where,$value);

            } else {

                $res = $this->add($value);
            }

            if ($res === false) {

                return false;
            }
            $buttonFront[] = $value['front_key'];
        }

        //删除多余button
        $count = $this->model->where(['foreign_keys_id'=>$foreignKeysId,'type'=>$type])->count();
        if ($count > count($buttonFront)) {

            $delWhere[]                         = ['key','>',count($buttonFront)];
            $delWhere['foreign_keys_id']        = $foreignKeysId;
            $delWhere['type']                   = $type;

            $delRes = $this->delete($delWhere);
            if ($delRes === false) {

                return false;
            }
        }

        return true;
    }

    /**
     * 先对以前进行排序
     * @param $foreignKeysId
     * @return bool
     */
    private function sortButton($foreignKeysId){

        $where['foreign_keys_id']       = $foreignKeysId;

        $res = $this->model->where($where)->orderBy('key','asc')->get();
        if ($res) {

            foreach ($res as $key=>$value) {


                $updateRes = $this->model->where('id',$value->id)->update(['key'=>($key + 1)]);
                if ($updateRes === false) {

                    return false;
                }
            }
        }

        return true;
    }
}