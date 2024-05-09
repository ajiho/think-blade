<?php

namespace ajiho\blade\core;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler as BaseBladeCompiler;

class BladeCompiler extends BaseBladeCompiler
{
    public function __construct(Filesystem $files, $cachePath)
    {
        parent::__construct($files, $cachePath);

        $this->echoFormat = '\Illuminate\Support\e(%s)';

    }

    public function setRawTags(array $tags)
    {
        $this->rawTags = $tags;
    }

    public function setContentTags(array $tags)
    {
        $this->contentTags = $tags;
    }

    public function setEscapedTags(array $tags)
    {
        $this->escapedTags = $tags;
    }

    public function setCachePath($path)
    {
        $this->cachePath = $path;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    public function withDoubleEncoding()
    {
        $this->setEchoFormat('\Illuminate\Support\e(%s, true)');
    }

    public function withoutDoubleEncoding()
    {
        $this->setEchoFormat('\Illuminate\Support\e(%s, false)');
    }
}
