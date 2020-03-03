<?php

// require_once('include/SpiceFTSManager/SpiceFTSRESTManager.php');

$spiceFTSManager = new SpiceCRM\includes\SpiceFTSManager\SpiceFTSRESTManager();

$app->group('/ftsmanager', function () use ($app, $spiceFTSManager) {
    $app->group('/core', function () use ($app, $spiceFTSManager) {
        $app->get('/modules', function () use ($app, $spiceFTSManager) {
            echo json_encode($spiceFTSManager->getModules());
        });
        $app->get('/index', function () use ($app, $spiceFTSManager) {
            echo json_encode($spiceFTSManager->getIndex());
        });
        $app->get('/nodes', function () use ($app, $spiceFTSManager) {
            $getParams = $_GET;
            echo json_encode($spiceFTSManager->getNodes($getParams['nodeid']));
        });
        $app->get('/fields', function () use ($app, $spiceFTSManager) {
            $getParams = $_GET;
            echo json_encode($spiceFTSManager->getFields($getParams['nodeid']));
        });
        $app->get('/analyzers', function () use ($app, $spiceFTSManager) {
            echo json_encode($spiceFTSManager->getAnalyzers());
        });
        $app->post('/initialize', function () use ($app, $spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new \KREST\ForbiddenException();
            }

            echo json_encode($spiceFTSManager->initialize());
        });
    });
    $app->group('/{module}', function() use ($app, $spiceFTSManager) {
        $app->get('/fields', function($req, $res, $args) use ($app, $spiceFTSManager) {
            echo json_encode($spiceFTSManager->getFTSFields($args['module']));
        });
        $app->get('/settings', function($req, $res, $args) use ($app, $spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new \KREST\ForbiddenException();
            }

            echo json_encode($spiceFTSManager->getFTSSettings($args['module']));
        });
        $app->delete('', function($req, $res, $args) use ($app, $spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new \KREST\ForbiddenException();
            }

            echo json_encode($spiceFTSManager->deleteIndexSettings($args['module']));
        });
        $app->post('', function($req, $res, $args) use ($app, $spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new \KREST\ForbiddenException();
            }
            $items = $req->getParsedBody();

            // clear any session cached data for the module
            unset($_SESSION['SpiceFTS']['indexes'][$args['module']]);

            echo json_encode($spiceFTSManager->setFTSFields($args['module'], $items));
        });

        $app->group('/index', function() use ($app, $spiceFTSManager) {
            $app->post('', function ($req, $res, $args) use ($app, $spiceFTSManager) {
                // admin check
                global $current_user;
                if (!$current_user->is_admin) {
                    throw new \KREST\ForbiddenException();
                }

                $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();

                $params = $_GET;
                if ($params['resetIndexDates']) {
                    $ftsHandler->resetIndexModule($args['module']);
                }

                if ($params['bulkAmount'] != 0) {
                    ob_start();
                    $ftsHandler->indexModuleBulk($args['module'], $params['bulkAmount']); //CR1000257
                    $message = ob_get_clean();
                }

                echo json_encode(array('status' => 'success', 'message' => $message));
            });
            $app->delete('', function($req, $res, $args) use ($app, $spiceFTSManager) {
                // admin check
                global $current_user;
                if(!$current_user->is_admin) {
                    throw new \KREST\ForbiddenException();
                }

                echo json_encode($spiceFTSManager->deleteIndex($args['module']));
            });
            $app->post('/reset', function($req, $res, $args) use ($app, $spiceFTSManager) {
                // admin check
                global $current_user;
                if(!$current_user->is_admin) {
                    throw new \KREST\ForbiddenException();
                }

                $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();

                // delete and recreate the index
                $spiceFTSManager->deleteIndex($args['module']);
                $mapResults = $spiceFTSManager->mapModule($args['module']);

                if(!$mapResults->acknowledged){
                    echo json_encode(array(
                        'status' => 'error',
                        'type' => $mapResults->error->type,
                        'message' => $mapResults->error->reason,
                    ));
                    return;
                }

                // index the beans
                $ftsHandler->resetIndexModule($args['module']);

                echo json_encode(array('status' => 'success'));
            });
        });
    });
});
