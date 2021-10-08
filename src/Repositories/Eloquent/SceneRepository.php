<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\Scene;
use Yjtec\PanoEdit\Repositories\Contracts\SceneInterface;
use Yjtec\Repo\Repository;

class SceneRepository extends Repository implements SceneInterface
{
    public function model()
    {
        return Scene::class;
    }
}