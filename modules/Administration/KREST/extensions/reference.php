<?php
$app->group('/reference', function()
{
    $this->get('', function($req, $res, $args){
        $getJSONcontent = file_get_contents('https://packages.spicecrm.io/referenceconfig');

        //BEGIN CR1000048 maretval: catch version and modify depending on reference / release
        if(!class_exists('SpiceUIConfLoader', false))
            require_once 'modules/SystemUI/SpiceUIConfLoader.php';
        $loader = new SpiceUIConfLoader();
        if($loader->release === true){
            $content = json_decode($getJSONcontent);
            $content->versions = array();
            $content->versions[0]->version = $GLOBALS['sugar_version'];
            $getJSONcontent = json_encode($content);
        }
        unset($loader);
        //END

        return $res->write($getJSONcontent);
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
            $route = $loader->routebase;
            $package = (isset($params['package']) ? $params['package'] : "*");
            $version = (isset($params['version']) ? $params['version'] : "*");
            $endpoint = implode("/", array($route, $package, $version));
            $results = $loader->loadDefaultConf($endpoint, array('route' => $route, 'package' => $package, 'version' => $version));
            $loader->cleanDefaultConf();
            return $res->withJson($results);
        });
    });
});