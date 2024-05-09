<?php

namespace ajiho\blade;



class Service extends \think\Service
{
    public function register()
    {

        $this->app->bind('blade', new Blade($this->app));




    }
}
