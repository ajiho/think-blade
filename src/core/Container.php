<?php

namespace ajiho\blade\core;

use Closure;
use Illuminate\Container\Container as BaseContainer;

class Container extends BaseContainer
{
    protected $terminatingCallbacks = [];

    public function terminating(Closure $callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    public function terminate()
    {
        foreach ($this->terminatingCallbacks as $terminatingCallback) {
            $terminatingCallback();
        }
    }
}
