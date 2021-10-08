<?php
namespace Yjtec\PanoEdit\Facades;
use Illuminate\Support\Facades\Facade;
class PanoEdit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'edit_pano';
    }
}