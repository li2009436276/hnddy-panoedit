<?php


namespace Yjtec\PanoEdit\Controllers;


use Yjtec\PanoEdit\Repositories\Contracts\SandTableInterface;
use Yjtec\PanoEdit\Resources\ErrorResource;
use Yjtec\PanoEdit\Services\SandTable\SandTableService;

class XmlPluginController extends Controller
{


    public function __construct()
    {

    }

    public function plugin($plugin){

        $sandTableService = new SandTableService(1567);
        $res = $sandTableService->makeXml(null,false,[]);

        if ($res) {

            return response($res,200)->header('Content-type','text/xml');
        }

        return new ErrorResource([]);
    }
}