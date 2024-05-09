# think-blade

基于thinkphp6封装的smarty模板引擎


| **Blade** | **think-blade** |
|-----------|-----------------|
| ^7.0      | ^1.0            |

## 为什么要封装think-blade

- Blade模板引擎在phpstorm高亮友好
- Blade模板语法更优雅，也更强大
- 从thinkphp6开始默认不集成模板引擎，可以选择自己喜欢的模板引擎



## 安装

```
composer require ajiho/think-blade
```

## 配置文件

安装完毕后会自动生成`/config/blade.php`

```php
<?php

return [
    //原始标记
    'rawTags' => ['{!!', '!!}'],

    //内容标记
    'contentTags' => ['{{', '}}'],

    //转移标记
    'escapedTags' => ['{{{', '}}}'],

    // 模板目录名
    'view_dir_name' => 'view',

    // 模板编译目录名
    'compile_dir_name' => 'templates_compile',
];
```

## 基本用法

```php
use ajiho\blade\facade\Blade;


//全局分配变量
Blade::share('user2', '123');


//给指定的模板分配变量
Blade::composer("main.sidebar", function($view) {
    $view->with("links", 'github.com');
});

// 定义指令
Blade::directive('strlen', function ($parameter) {
    return "<?php echo strlen($parameter) ?>";
});




Blade::make('user.add', ['name' => 'John Doeccc'])->render()

//支持跨应用渲染
Blade::make('admin@user.add', ['name' => 'John Doeccc'])->render()

//或者直接使用快捷方法render
Blade::render('user.index', ['name' => 'John Doeccc'])
```

tips::用了think-blade，你需要把thinkphp自带的视图相关的配置文件view.php,或者View::fetch
之类的方法完全抛之脑后

因为，该包的封装，并不是通过实现think\contract\TemplateHandlerInterface接口实现的



## 文档

[blade](https://laravel.com/docs/7.x/blade)
