<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\HotspotArticle;
use Yjtec\PanoEdit\Repositories\Contracts\HotspotArticleInterface;
use Yjtec\Repo\Repository;

class HotspotArticleRepository extends Repository implements HotspotArticleInterface
{
    public function model()
    {
        return HotspotArticle::class;
    }
}