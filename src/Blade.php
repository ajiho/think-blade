<?php


namespace ajiho\blade;


use Illuminate\Contracts\View\View;
use think\App;
use ajiho\blade\core\Blade as BaseBlade;

class Blade extends BaseBlade
{

    /**
     * @var App
     */
    private $app;


    protected $config = [
        // 模板目录名
        'view_dir_name' => 'view',

        // 模板编译目录名
        'compile_dir_name' => 'templates_compile',

        //原始标记
        'rawTags' => ['{!!', '!!}'],

        //内容标记
        'contentTags' => ['{{', '}}'],

        //转移标记
        'escapedTags' => ['{{{', '}}}'],
    ];


    public function __construct(App $app)
    {

        parent::__construct();

        $this->app = $app;

        //配置合并
        $this->config = array_merge($this->config, $this->app->config->get('blade', []));


        //设置标记
        $this->compiler()->setContentTags($this->config['contentTags']);
        $this->compiler()->setRawTags($this->config['rawTags']);
        $this->compiler()->setEscapedTags($this->config['escapedTags']);

    }


    public function make($view, $data = [], $mergeData = []): View
    {
        if (strpos($view, '@')) {
            // 跨模块调用
            list($app, $template) = explode('@', $view);

            $view = $template;

        }

        if (isset($app)) {//如果是跨模块调用

            $viewPath = $this->app->getBasePath() . $app . DIRECTORY_SEPARATOR . $this->config['view_dir_name'] . DIRECTORY_SEPARATOR;

            if (is_dir($viewPath)) {//先判断app目录下面的应用的view目录是否存在
                $path = $viewPath;
            } else {//如果不存在则调用项目根目录下的view/应用目录
                $path = $this->app->getRootPath() . $this->config['view_dir_name'] . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR;
            }

        } else {//非跨模块调用的情况

            if (is_dir($this->app->getAppPath() . $this->config['view_dir_name'])) {//判断app目里是否有视图目录
                $path = $this->app->getAppPath() . $this->config['view_dir_name'] . DIRECTORY_SEPARATOR;
            } else {
                $appName = $this->app->http->getName();
                $path = $this->app->getRootPath() . $this->config['view_dir_name'] . DIRECTORY_SEPARATOR . ($appName ? $appName . DIRECTORY_SEPARATOR : '');
            }
        }

        $viewPaths = [$path];
        $this->setTemplateDir($viewPaths);
        //设置缓存路径
        $this->setCachePath($this->app->getRuntimePath() . $this->config['compile_dir_name']);

        return parent::make($view, $data, $mergeData);

    }


}
