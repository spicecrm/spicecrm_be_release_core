<?php
namespace SpiceCRM\includes\SpiceTemplateCompiler;

class System
{
    private static $plugin_directories = [
        'include/SpiceTemplateCompiler/plugins',
        'custom/include/SpiceTemplateCompiler/plugins',
    ];

    public function __construct()
    {
        $this->loadPlugins();
    }

    public function __call($method, $args)
    {
        /*
        if(method_exists($this, $method))
            return call_user_func([$this, $method], $args);
        */
        // try to use form custom plugins
        $call = "\SpiceCRM\custom\includes\SpiceTemplateCompiler\plugins\\$method";
        if(function_exists($call))
            return call_user_func($call, $args);

        // try to use from core plugins
        $call = "SpiceCRM\includes\SpiceTemplateCompiler\plugins\\$method";
        if(function_exists($call))
            return call_user_func($call, $args);

        return false;
        //throw new Exception("No method or function found to use!");
    }

    /**
     * because autoloading of functions doesn't work in PHP/autoloader, all files in the dedicated directories will be included, so the functions will be available in their respective namespaces.
     */
    private function loadPlugins()
    {
        foreach(static::$plugin_directories as $dir)
        {
            $dir_handler = opendir($dir);
            if(!$dir_handler)
                continue;

            while (false !== ($entry = readdir($dir_handler)))
            {
                if(strpos($entry, '.php') === false)
                    continue;

                include_once("$dir/$entry");
            }

            closedir($dir_handler);
        }
    }
}