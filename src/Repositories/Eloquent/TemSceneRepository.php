<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use App\Models\Template\TemScenes;
use Yjtec\PanoEdit\Repositories\Contracts\TemSceneInterface;
use Yjtec\Repo\Repository;

class TemSceneRepository extends Repository implements TemSceneInterface
{
    public function model(){

        return TemScenes::class;
    }

}