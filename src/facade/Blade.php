<?php

namespace ajiho\blade\facade;

use think\Facade;

class Blade extends Facade{


    protected static function getFacadeClass()
    {
        return 'blade';
    }
}
