<?php
$app->group('/configurator', function () use ($app) {
    $app->group('/editor', function () use ($app) {
        $app->get('/{category}', function ($req, $res, $args) use ($app) {
            global $current_user, $db, $sugar_config;

            # header("Access-Control-Allow-Origin: *");
            if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

            $retArray = $sugar_config[$args['category']] ?: new stdClass();

            /*
            $entries = $db->query("SELECT * FROM config WHERE category = '{$args['category']}'");
            while ($entry = $db->fetchByAssoc($entries)) {
                $retArray[$entry['name']] = $entry['value'];
            }
            */

            echo json_encode($retArray);
        });
        $app->post('/{category}', function ($req, $res, $args) use ($app) {
            global $current_user, $db, $sugar_config;

            # header("Access-Control-Allow-Origin: *");
            if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

            $postBody = $req->getParsedBody();

            foreach($postBody as $name => $value){
                // write sgar config
                $sugar_config[$args['category']][$name] = $value;

                // write to database
                $entry = $db->fetchByAssoc($db->query("SELECT * FROM config WHERE category='{$args['category']}' AND name='{$name}'"));
                if($entry){
                    $db->query("UPDATE config SET value='{$value}' WHERE category='{$args['category']}' AND name='{$name}'");
                } else {
                    $db->query("INSERT INTO config (category, name, value) VALUES('{$args['category']}', '{$name}', '{$value}')");
                }
            }

            echo json_encode($postBody);
        });
    });
    $app->get('/entries/{table}', function ($req, $res, $args) use ($app) {
        global $current_user, $db;

        # header("Access-Control-Allow-Origin: *");
        if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

        $retArray = [];

        $entries = $db->query("SELECT * FROM {$args['table']}");
        while ($entry = $db->fetchByAssoc($entries)) {
            $retArrayEntry = [];
            foreach ($entry as $key => $value) {
                $retArrayEntry[$key] = html_entity_decode($value);
            }

            $retArray[] = $retArrayEntry;
        }

        echo json_encode($retArray);
    });
    $app->delete('/{table}/{id}', function ($req, $res, $args) use ($app) {
        global $current_user, $db;

        # header("Access-Control-Allow-Origin: *");
        if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

        include('modules/TableDictionary.php');
        foreach ($dictionary as $meta) {
            if ($meta['table'] == $args['table']) {
                // check if we have a CR set
                if ($meta['changerequests']['active'] && $_SESSION['SystemDeploymentCRsActiveCR'])
                    $cr = BeanFactory::getBean('SystemDeploymentCRs', $_SESSION['SystemDeploymentCRsActiveCR']);

                if($cr){
                    $record = $db->fetchByAssoc($db->query("SELECT * FROM {$args['table']} WHERE id = '{$args['id']}'"));
                    if(is_array($meta['changerequests']['name'])){
                        $nameArray = [];
                        foreach($meta['changerequests']['name'] as $item){
                            $nameArray[]=$record['item'];
                        }
                        $cr->addDBEntry($args['table'], $args['id'], 'D', implode('/', $nameArray));
                    } else {
                        $cr->addDBEntry($args['table'], $args['id'], 'D', $record[$meta['changerequests']['name']]);
                    }
                }


            }
        }

        $db->query("DELETE FROM {$args['table']} WHERE id = '{$args['id']}'");

        echo json_encode(['status' => 'success']);
    });
    $app->post('/{table}/{id}', function ($req, $res, $args) use ($app) {
        global $current_user, $db;

        if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
        # header("Access-Control-Allow-Origin: *");

        $postBody = $req->getParsedBody();

        $setArray = [];
        foreach ($postBody as $field => $value) {
            if ($field !== 'id' && $value !== "")
                $setArray[] = sprintf('`%s` = "%s"', $field, $db->quote( $value ));
        }

        // no error handling, fire and forget :)
        if (count($setArray) > 0) {
            $exists = $db->fetchByAssoc($db->query("SELECT id FROM {$args['table']} WHERE id='{$args['id']}'"));
            if ($exists) {
                $db->query("UPDATE {$args['table']} SET " . implode(',', $setArray) . " WHERE id='{$args['id']}'");
            } else {
                $setArray[] = "id='{$args['id']}'";
                $db->query("INSERT INTO {$args['table']} SET " . implode(',', $setArray));
            }

            // check for CR relevancy
            include('modules/TableDictionary.php');
            foreach ($dictionary as $meta) {
                if ($meta['table'] == $args['table']) {
                    // check if we have a CR set
                    if ($meta['changerequests']['active'] && $_SESSION['SystemDeploymentCRsActiveCR'])
                        $cr = BeanFactory::getBean('SystemDeploymentCRs', $_SESSION['SystemDeploymentCRsActiveCR']);

                    if($cr){
                        if(is_array($meta['changerequests']['name'])){
                            $nameArray = [];
                            foreach($meta['changerequests']['name'] as $item){
                                $nameArray[]=$postBody['item'];
                            }
                            $cr->addDBEntry($args['table'], $args['id'], $exists ? 'U' : 'I', implode('/', $nameArray));
                        } else {
                            $cr->addDBEntry($args['table'], $args['id'], $exists ? 'U' : 'I', $postBody[$meta['changerequests']['name']]);
                        }
                    }

                }
            }

        }

        echo json_encode(['status' => 'success']);
    });
    $app->post('/update', function ($req, $res, $args) use ($app) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        $data = array_merge($postBody, $postParams);
        echo json_encode(['status' => 'success']);
    });


    $app->get('/load', function($req, $res, $args) use($app){
        global $current_user;
        if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

        $params = $_GET;
        if(!class_exists('SpiceUIConfLoader', false))
            require_once 'modules/SystemUI/SpiceUIConfLoader.php';
        $loader = new SpiceUIConfLoader();
        $route = $loader->routebase;
        $packages = explode(",", $params['packages']);
        $versions = (!empty($params['versions']) ? $params['versions'] : "*");
        $endpoint = implode("/", array($route, implode(",", $packages), $versions));
        $results = $loader->loadDefaultConf($endpoint, array('route' => $route, 'packages' => $packages, 'versions' => $versions));
        $loader->cleanDefaultConf();
        echo json_encode($results);
    });

    $app->get('/objectrepository', function($req, $res, $args) use($app){
        global $db;

        $db->query('SET SESSION group_concat_max_len = 1000000;');
        $sql = 'SELECT CONCAT("\'", group_concat(item ORDER BY item SEPARATOR "\',\'"), "\'") FROM (SELECT component item FROM sysuiobjectrepository UNION SELECT component item FROM sysuicustomobjectrepository UNION SELECT module item FROM sysuimodulerepository UNION SELECT module item FROM sysuicustommodulerepository) x;';
        
        $dbResult = $db->query( $sql );
        $row = $db->fetchByAssoc( $dbResult );

        return $res->withJson([ 'repostring' => array_pop( $row ) ]);
    });

    $uiRestHandler = new SpiceCRM\modules\SystemUI\SystemUIRESTHandler();
    $app->group('/roles', function () use ($app, $uiRestHandler) {

        $app->get('/{userid}', function ($req, $res, $args) use ($app, $uiRestHandler) {
            echo json_encode($uiRestHandler->getAllRoles($args['userid']));
        });
        $app->post('/{roleid}/{userid}/{default}', function ($req, $res, $args) use ($app, $uiRestHandler) {
            global $current_user;
            if (!$current_user->is_admin) throw (new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
            echo json_encode($uiRestHandler->setUserRole($args));
        });
        $app->delete('/{roleid}/{userid}', function ($req, $res, $args) use ($app, $uiRestHandler) {
            global $current_user;
            if (!$current_user->is_admin) throw (new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
            echo json_encode($uiRestHandler->deleteUserRole($args));
        });
    });

});