<?php

namespace AM2Studio\Laravel\SeoMeta;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dispensary;
use App\Models\BaseModel;

/**
 * Class SeoMetaServiceProvider
 *
 * @package AM2Studio\Laravel\SeoMeta
 */
class SeoMetaServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerEvents();
    }

    /**
     * Register the listener events
     *
     * @return void
     */
    public function registerEvents()
    {
        $this->app['events']->listen('eloquent.created*', function ($model) {
            if ($model instanceof SeoMetaInterface) {
                $model->seoMetaModelCreated();
            }
        });

        $this->app['events']->listen('eloquent.updating*', function ($model) {
            if ($model instanceof SeoMetaInterface) {
                $model->seoMetaModelUpdating();
            }
        });

        $this->app['events']->listen('eloquent.updated*', function ($model) {
            if ($model instanceof SeoMetaInterface) {
                $model->seoMetaModelUpdated();
            }
        });
    }
}
