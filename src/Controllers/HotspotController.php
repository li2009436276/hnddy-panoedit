<?php


namespace Yjtec\PanoEdit\Controllers;


use Yjtec\PanoEdit\Repositories\Contracts\HotspotArticleInterface;

class HotspotController extends Controller
{
    private $hotspotArticle = null;

    public function __construct(HotspotArticleInterface $hotspotArticle)
    {
        $this->hotspotArticle = $hotspotArticle;
    }

    /**
     * @OA\Get(
     *     path="/hotspot/hotspotArticle/{id}",
     *     description="富文本热点html",
     *     summary="富文本热点html",
     *     tags={"Hotspot"},
     *     @OA\Parameter( description="文章id", in="path", name="id", required=true,@OA\Schema( type="integer"),example=1),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent( ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"token": {}}
     *     }
     * )
     */
    public function hotspotArticle($id){

        $articleInfo = $this->hotspotArticle->index(['id'=>$id]);

        if ($articleInfo) {

            $content = htmlspecialchars_decode($articleInfo['content']);

            $content = preg_replace(
                array(
                    '/(<img [^<>]*?)src=/',
                    '/(<img.*?)((height)=[\'"]+[0-9|%]+[\'"]+)/',
                ) , array( '$1 style="max-width:100%;" src=','$1') , $content);


            return '<html><body style="margin:0;"><div style="background: white;padding: 10px;min-height: 1000px;">'.$content.'</div></body></html>';
        }

        return '';
    }
}