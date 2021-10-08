<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\EventData;
use Yjtec\PanoEdit\Repositories\Contracts\EventDataInterface;
use Yjtec\Repo\Repository;

class EventDataRepository extends Repository implements EventDataInterface
{

    public function model()
    {
        return EventData::class;
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreate($id = 0,$data = [])
    {
        $res = $this->model->updateOrCreate(['id'=>$id],$data);
        return $res;
    }
}