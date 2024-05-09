<?php

namespace ajiho\blade\core;

use Illuminate\View\ViewServiceProvider as BaseViewServiceProvider;

class ViewServiceProvider extends BaseViewServiceProvider
{


    public function registerBladeCompiler()
    {

        $this->app->singleton('blade.compiler', function ($app) {

            return new BladeCompiler($app['files'], $app['config']['view.compiled']);

        });
    }
}
