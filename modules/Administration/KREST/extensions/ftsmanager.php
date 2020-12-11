<?php
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSRESTManager;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$spiceFTSManager = new SpiceFTSRESTManager();

$RESTManager->app->group('/ftsmanager', function () use ($spiceFTSManager) {
    $this->group('/core', function () use ($spiceFTSManager) {
        $this->get('/modules', function () use ($spiceFTSManager) {
            echo json_encode($spiceFTSManager->getModules());
        });
        $this->get('/index', function () use ($spiceFTSManager) {
            echo json_encode($spiceFTSManager->getIndex());
        });
        $this->get('/nodes', function () use ($spiceFTSManager) {
            $getParams = $_GET;
            echo json_encode($spiceFTSManager->getNodes($getParams['nodeid']));
        });
        $this->get('/fields', function () use ($spiceFTSManager) {
            $getParams = $_GET;
            echo json_encode($spiceFTSManager->getFields($getParams['nodeid']));
        });
        $this->get('/analyzers', function () use ($spiceFTSManager) {
            echo json_encode($spiceFTSManager->getAnalyzers());
        });
        $this->post('/initialize', function () use ($spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new ForbiddenException();
            }

            echo json_encode($spiceFTSManager->initialize());
        });
    });
    $this->group('/{module}', function() use ($spiceFTSManager) {
        $this->get('/fields', function($req, $res, $args) use ($spiceFTSManager) {
            echo json_encode($spiceFTSManager->getFTSFields($args['module']));
        });
        $this->get('/settings', function($req, $res, $args) use ($spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new ForbiddenException();
            }

            echo json_encode($spiceFTSManager->getFTSSettings($args['module']));
        });
        $this->delete('', function($req, $res, $args) use ($spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new ForbiddenException();
            }

            echo json_encode($spiceFTSManager->deleteIndexSettings($args['module']));
        });
        $this->post('', function($req, $res, $args) use ($spiceFTSManager) {
            // admin check
            global $current_user;
            if(!$current_user->is_admin) {
                throw new ForbiddenException();
            }
            $items = $req->getParsedBody();

            // clear any session cached data for the module
            unset($_SESSION['SpiceFTS']['indexes'][$args['module']]);

            echo json_encode($spiceFTSManager->setFTSFields($args['module'], $items));
        });

        $this->group('/index', function() use ($spiceFTSManager) {
            $this->post('', function ($req, $res, $args) use ($spiceFTSManager) {
                // admin check
                global $current_user;

                set_time_limit(300);

                if (!$current_user->is_admin) {
                    throw new ForbiddenException();
                }

                $ftsHandler = new SpiceFTSHandler();

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
            $this->delete('', function($req, $res, $args) use ($spiceFTSManager) {
                // admin check
                global $current_user;
                if(!$current_user->is_admin) {
                    throw new ForbiddenException();
                }

                echo json_encode($spiceFTSManager->deleteIndex($args['module']));
            });
            $this->post('/reset', function($req, $res, $args) use ($spiceFTSManager) {
                // admin check
                global $current_user;
                if(!$current_user->is_admin) {
                    throw new ForbiddenException();
                }

                $ftsHandler = new SpiceFTSHandler();

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
