<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


use phpseclib\System\SSH\Agent\Identity;

interface EventDataInterface
{
    /**
     * 更新事件
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($id = 0,$data = []);
}