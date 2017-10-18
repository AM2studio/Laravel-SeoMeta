<?php

namespace AM2Studio\Laravel\SeoMeta;

use Illuminate\Support\ServiceProvider;

/**
 * Class SeoMetaServiceProvider.
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
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerEvents();
    }

    /**
     * Register the listener events.
     */
    public function registerEvents()
    {
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
