<?php


namespace Yjtec\PanoEdit\Controllers;


use Illuminate\Http\Request;
use Yjtec\PanoEdit\Repositories\Contracts\HotspotPolygonsInterface;

class HotspotPolygonsController extends Controller
{
    private $hotspotPolygonsInterface = NULL;
    public function __construct(HotspotPolygonsInterface $hotspotPolygonsInterface)
    {
        $this->hotspotPolygonsInterface = $hotspotPolygonsInterface;

    }

    /**
     * 添加多边形热点
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request){

        $res = $this->hotspotPolygonsInterface->add();
        return $res;
    }

    /**
     * 获取多边形热点信息
     * @param Request $request
     * @return mixed
     */
    public function info(Request $request){

        $where['id']    = $request->id;
        $res = $this->hotspotPolygonsInterface->info($where);
        return $res;
    }
}