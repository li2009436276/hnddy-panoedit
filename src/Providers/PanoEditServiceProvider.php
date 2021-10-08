<?php

namespace Yjtec\PanoEdit\Providers;

use Illuminate\Support\ServiceProvider;

class PanoEditServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
            __DIR__ . '/../database/seeds/'      => database_path('seeds'),
            __DIR__ . '/../config/' => config_path(),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\ButtonInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\ButtonRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\ButtonTemplateInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\ButtonTemplateRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\HotspotPolygonsInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\HotspotPolygonsRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\HotspotPolygonsPointInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\HotspotPolygonsPointRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\ProjectInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\ProjectRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\ProjectActionInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\ProjectActionRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\ProjectTemplateInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\ProjectTemplateRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\EmbedInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\EmbedRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\EmbedImgInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\EmbedImgRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\FileInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\FileRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\HotspotInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\HotspotRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\RingsInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\RingsRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\EventDataInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\EventDataRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\HotspotArticleInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\HotspotArticleRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\RadarContainerInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\RadarContainerRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\RadarInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\RadarRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\SandTableInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\SandTableRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\Model3dInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\Model3dRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\Model3dFileInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\Model3dFileRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\Model3dClassifyInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\Model3dClassifyRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\ProjectCommentInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\ProjectCommentRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\SceneInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\SceneRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\SceneDetailInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\SceneDetailRepository');
        $this->app->bind('Yjtec\PanoEdit\Repositories\Contracts\RichTextInterface', 'Yjtec\PanoEdit\Repositories\Eloquent\RichTextRepository');

    }
}
