<?php

namespace LuckyMedia\BusinessHours;

use LuckyMedia\BusinessHours\Tags\BusinessHoursTags;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $tags = [
        BusinessHoursTags::class,
    ];

    protected $translations = true;

    public function bootAddon()
    {
        parent::boot();

        Nav::extend(function ($nav) {
            $nav->content(__("business-hours::settings.name"))
                ->route('luckymedia.businesshours.index')
                ->icon('time');
        });

        $this->mergeConfigFrom(__DIR__.'/../config/business_hours.php', 'statamic.business_hours');

        $this->publishes([
            __DIR__.'/../config/business_hours.php' => config_path('statamic/business_hours.php'),
        ], 'luckymedia-businesshours-config');

        if (!file_exists(base_path(config('statamic.business_hours.path')))) {
            $this->publishes([
                __DIR__.'/../content/addons/business_hours.yaml' => config('statamic.business_hours.path'),
            ], 'luckymedia-businesshours-content');
        };
    }
}
