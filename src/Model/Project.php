<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yjtec\PanoEdit\Model\ProjectAction;

class Project extends Model
{

    use SoftDeletes;

    /**
     *  @OA\Schema(
     *      schema="YjtecProject",
     *      @OA\Property(property="id",                    type="integer",     description="ID"),
     *      @OA\Property(property="user_id",               type="integer",     description="用户id"),
     *      @OA\Property(property="classify_id",           type="integer",     description="系统分类ID"),
     *      @OA\Property(property="group_id",              type="integer",     description="分组ID"),
     *      @OA\Property(property="name",                  type="string",      description="图片名称"),
     *      @OA\Property(property="labels",                type="string",      description="项目标签"),
     *      @OA\Property(property="thumb",                 type="string",      description="项目缩略图地址"),
     *      @OA\Property(property="view",                  type="integer",     description="浏览量"),
     *      @OA\Property(property="good",                  type="integer",     description="点赞量"),
     *      @OA\Property(property="download",              type="integer",     description="下载量"),
     *      @OA\Property(property="status",                type="integer",     description="状态：1：正常，2：发布，3：禁用"),
     *
     *  )
     *
     * @OA\Schema(
     *      schema="YjtecProjectLists",
     *      allOf= {
     *          @OA\Schema(
     *              @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/YjtecProject"))
     *          ),
     *          @OA\Schema(ref="#/components/schemas/Page"),
     *      }
     *  )
     */
    protected $connection = 'mysql';
    protected $table = 'projects';
    protected $fillable = [
        'id','user_id','group_id','classify_id','name','labels','thumb','description','view','good','download','marq_text','status'
        ,'status_start','status_welcome','welcome','status_full_welcome','bigwel','recommend','public_status','xml_path','selected'
    ];

   /* protected $hidden = [
        'user_id'
    ];*/

   public function __construct(array $attributes = [])
   {

       if (config('pano_edit.project') == 'vryun') {

           $this->table = 'projects2';
       }

       parent::__construct($attributes);
   }

    public function getThumbAttribute($value){

        if (strpos(' '.$value,'panos')){

            return array( 'url' => \Storage::url($value),'path' => $value);
        }

        return array( 'url' => \Storage::url(env('APP_ENV').$value),'path' => $value);
    }

    /**
     * 项目下的分组
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category(){
        return $this->hasMany(ProjectSceneGroup::class,'project_id','id')->select(['id','project_id','name']);
    }

    /**
     * 获取项目下的场景
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scenes(){

        return $this->hasMany(Scene2::class,'project_id','id')->with('view','hotspot','effect','level','music','mask','comment','hotspot_polygons','embed')->groupBy('group_id')->groupBy('id')->orderBy('group_id','asc')->orderBy('key','asc')->select(['id','project_id','panorama_id','group_id','name','thumb','path','domain','scene_image','scene_preview','key']);
    }

    /**
     * 获取项目下的button
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buttons(){
        return $this->hasMany(Button::class,'foreign_keys_id','id')->where(['type' => 1])->with('classify')->select(['id','classify_id','front_key','foreign_keys_id','container','icon','css','text','type','key','status','event_id']);
    }

    /**
     * 获取平面容器
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function radar_container(){

        return $this->hasOne(RadarContainer::class,'foreign_keys_id','id')->with('radar')->select(['id','foreign_keys_id','front_key','container','bg_img']);
    }

    /**
     * 获取分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classify(){
        return $this->belongsTo(ProjectClassify::class,'classify_id','id')->select(['id','name']);
    }

    /**
     * 单独获取用户评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scene_comment(){
        return $this->hasMany(Scene2::class,'project_id','id')->with('comment')->orderBy('key','asc')->select(['id','project_id','key']);
    }

    /**
     * 获取项目导航信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function project_navigation(){
        return $this->hasOne(ProjectNavigation::class,'project_id','id')->select(['project_id','province','city','district','address','lng','lat']);
    }

    /**
     * 获取功能ID
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function project_action(){
        return $this->hasMany(ProjectAction::class,'project_id','id')->with('action_classify')->select(['id','project_id','action_id','value','key']);
    }
}