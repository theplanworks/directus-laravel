<?php

namespace thePLAN\DirectusLaravel;

use Illuminate\Support\ServiceProvider;

/**
 *
 * Laravel wrapper for Directus API
 *
 * @category   Laravel Directus
 * @version    1.0.0
 * @package    theplanworks/directus-laravel
 * @copyright  Copyright (c) 2017 thePLAN (http://www.theplanworks.com)
 * @author     Matt Fox <matt.fox@theplanworks.com>
 * @license    https://opensource.org/licenses/MIT    MIT
 */
class DirectusLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DirectusLaravel::class, function ($app) {
            return new DirectusLaravel();
        });
    }

    public function provides()
    {
        return [DirectusLaravel::class];
    }

}