<?php
$app->group('/reference', function()
{
    $this->get('', function($req, $res, $args){
        return $res->write(file_get_contents('https://packages.spicecrm.io/referenceconfig'));
    });

    $this->group('/load', function()
    {
        $this->get('/languages/{languages}', function($req, $res, $args){
            require_once 'modules/SystemLanguages/SystemLanguagesRESTHandler.php';
            $handler = new SystemLanguagesRESTHandler();
            $params = $_GET;
            $params['languages'] = $args['languages'];
            $result = $handler->loadSysLanguages($params);
            return $res->withJson($result);
        });
        $this->get('/configs', function($req, $res, $args){
            $params = $_GET;
            if(!class_exists('SpiceUIConfLoader', false))
                require_once 'modules/SystemUI/SpiceUIConfLoader.php';
            $loader = new SpiceUIConfLoader();
            $route = "referenceconfig";
            $package = (isset($params['package']) ? $params['package'] : "*");
            $version = (isset($params['version']) ? $params['version'] : "*");
            $endpoint = implode("/", array($route, $package, $version));
            $results = $loader->loadDefaultConf($endpoint, array('route' => $route, 'package' => $package, 'version' => $version));
            $loader->cleanDefaultConf();
            return $res->withJson($results);
        });
    });
});