<?php
namespace SpiceCRM\includes\SpiceTemplateCompiler\plugins;

function frontend_url()
{
    global $sugar_config;
    $frontend_url = $sugar_config['frontend_url'];
    if(substr($frontend_url, -1) == "/")
        $frontend_url = substr($frontend_url, 0, (strlen($frontend_url) -1));
    return $frontend_url;
}