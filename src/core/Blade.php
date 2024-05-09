<?php

namespace ajiho\blade\core;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Contracts\View\View;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;


class Blade implements FactoryContract
{
    /**
     * @var Application
     */
    protected $container;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var \ajiho\blade\core\BladeCompiler
     */
    private $compiler;


    public function __construct()
    {
        $this->container = new Container;


        $this->container->bindIf('files', function () {
            return new Filesystem;
        });

        $this->container->bindIf('events', function () {
            return new Dispatcher;
        });

        $viewPaths[] = getcwd() . DIRECTORY_SEPARATOR . 'templates';
        $cachePath = getcwd() . DIRECTORY_SEPARATOR . 'templates_compile';


        $this->container->bindIf('config', function () use ($viewPaths, $cachePath) {
            return new Repository([
                'view.paths' => $viewPaths,
                'view.compiled' => $cachePath,
            ]);
        });

        Facade::setFacadeApplication($this->container);

        (new ViewServiceProvider($this->container))->register();

        $this->factory = $this->container->get('view');
        $this->compiler = $this->container->get('blade.compiler');

    }


    public function setTemplateDir($viewPaths = [])
    {
        $this->factory->setFinder(new FileViewFinder($this->container['files'], $viewPaths));

    }

    public function setCachePath($path)
    {
        $this->compiler->setCachePath($path);
    }


    public function render(string $view, array $data = [], array $mergeData = []): string
    {

        return $this->make($view, $data, $mergeData)->render();
    }

    public function make($view, $data = [], $mergeData = []): View
    {

        $path = $this->compiler->getCachePath();

        //创建目录
        if (!$this->container['files']->isDirectory($path)) {
            $this->container['files']->makeDirectory($path, 0777, true, true);
        }

        return $this->factory->make($view, $data, $mergeData);
    }

    public function compiler(): BladeCompiler
    {
        return $this->compiler;
    }

    public function directive(string $name, callable $handler)
    {
        $this->compiler->directive($name, $handler);
    }

    public function if($name, callable $callback)
    {
        $this->compiler->if($name, $callback);
    }

    public function exists($view): bool
    {
        return $this->factory->exists($view);
    }

    public function file($path, $data = [], $mergeData = []): View
    {
        return $this->factory->file($path, $data, $mergeData);
    }

    public function share($key, $value = null)
    {
        return $this->factory->share($key, $value);
    }

    public function composer($views, $callback): array
    {
        return $this->factory->composer($views, $callback);
    }

    public function creator($views, $callback): array
    {
        return $this->factory->creator($views, $callback);
    }

    public function addNamespace($namespace, $hints): self
    {
        $this->factory->addNamespace($namespace, $hints);

        return $this;
    }

    public function replaceNamespace($namespace, $hints): self
    {
        $this->factory->replaceNamespace($namespace, $hints);

        return $this;
    }

    public function __call(string $method, array $params)
    {
        return call_user_func_array([$this->factory, $method], $params);
    }

}
