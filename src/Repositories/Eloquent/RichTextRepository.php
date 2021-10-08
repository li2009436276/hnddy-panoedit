<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\RichText;
use Yjtec\PanoEdit\Repositories\Contracts\RichTextInterface;
use Yjtec\Repo\Repository;

class RichTextRepository extends Repository implements RichTextInterface
{
    public function model()
    {
        return RichText::class;
    }
}