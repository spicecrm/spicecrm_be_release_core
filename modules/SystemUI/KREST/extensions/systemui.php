<?php
global $sugar_config;
use SpiceCRM\modules\SystemUI\SystemUIRESTHandler;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUILoadtasksController;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRepositoryController;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIComponentsetsController;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIActionsetsController;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRoutesController;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIFieldsetsController;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModelValidationsController;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$uiRestHandler = new SystemUIRESTHandler();

$RESTManager->registerExtension('spiceui', '2.0', $sugar_config['ui']);

$RESTManager->app->group('/spiceui', function () use ($uiRestHandler) {
    $this->group('/core', function () use ($uiRestHandler) {
        /**
         * handle the load tasks
         */
        $this->group('/loadtasks', function () use ($uiRestHandler) {
            /**
             * get all laod tasks defined in the system
             */
            $this->get('', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUILoadtasksController::getLoadTasks');
            /**
             * execute a specific loadtask as it is defined in the database
             */
            $this->get('/{loadtaskid}', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUILoadtasksController::executeLoadTask');
        });

        /**
         * all module related calls
         */
        $this->group('/modules/{module}/listtypes', function () use ($uiRestHandler) {
            $this->post('', function ($req, $res, $args) use ($uiRestHandler) {
                $postbody = $req->getParsedBody();
                echo json_encode($uiRestHandler->addListType($args['module'], $postbody['list'], $postbody['global']));
            });
            $this->post('/{id}', function ($req, $res, $args) use ($uiRestHandler) {
                $postbody = $req->getParsedBody();
                echo $uiRestHandler->setListType($args['id'], $postbody);
            });
            $this->delete('/{id}', function ($req, $res, $args) use ($uiRestHandler) {
                echo $uiRestHandler->deleteListType($args['id']);
            });
        });

        $this->get('/components', function ($req, $res, $args) use ($uiRestHandler) {
            echo json_encode(array(
                'modules'                 => SystemUIRepositoryController::getModuleRepository(),
                'components'              => SystemUIRepositoryController::getComponents(),
                'componentdefaultconfigs' => SystemUIRepositoryController::getComponentDefaultConfigs(),
                'componentmoduleconfigs'  => SystemUIRepositoryController::getComponentModuleConfigs(),
                'componentsets'           => SystemUIComponentsetsController::getComponentSets(),
                'actionsets'              => SystemUIActionsetsController::getActionSets(),
                'routes'                  => SystemUIRoutesController::getRoutesDirect(),
                'scripts'                 => SystemUIRepositoryController::getLibraries(),
            ));
        });

        $this->group('/roles', function () use ($uiRestHandler) {

            $this->get('/{userid}', function ($req, $res, $args) use ($uiRestHandler) {
                echo json_encode($uiRestHandler->getAllRoles($args['userid']));
            });
            $this->post('/{roleid}/{userid}/{default}', function ($req, $res, $args) use ($uiRestHandler) {
                global $current_user;
                if (!$current_user->is_admin) throw (new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
                echo json_encode($uiRestHandler->setUserRole($args));
            });
            $this->delete('/{roleid}/{userid}', function ($req, $res, $args) use ($uiRestHandler) {
                global $current_user;
                if (!$current_user->is_admin) throw (new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
                echo json_encode($uiRestHandler->deleteUserRole($args));
            });

        });

        $this->get('/componentmodulealreadyexists', function ($req, $res, $args) use ($uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->checkComponentModuleAlreadyExists($getParams));
        });
        $this->get('/componentdefaultalreadyexists', function ($req, $res, $args) use ($uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->checkComponentDefaultAlreadyExists($getParams));
        });

        $this->post('/componentsets', function ($req, $res, $args) use ($uiRestHandler) {
            $postbody = $req->getParsedBody();
            echo json_encode($uiRestHandler->setComponentSets($postbody));
        });
        $this->group('/fieldsets', function () use ($uiRestHandler) {
            $this->get('', function ($req, $res, $args) {
                $res->write(json_encode(['fieldsets' => SystemUIFieldsetsController::getFieldSets()]));
            });
            $this->post('', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIFieldsetsController::setFieldSets');
        });
        $this->group('/actionsets', function () use ($uiRestHandler) {
            $this->post('', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIActionsetsController::setActionSets');
        });
        $this->get('/fieldsetalreadyexists', function ($req, $res, $args) use ($uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->checkFieldSetAlreadyExists($getParams));
        });
        $this->get('/fielddefs', function ($req, $res, $args) use ($uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->getFieldDefs(json_decode($getParams['modules'])));
        });

        $this->group('/servicecategories', function () use ($uiRestHandler) {
            $this->get('', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->getServiceCategories();
                //var_dump($result);
                echo json_encode($result);
            });
            $this->get('/tree', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->getServiceCategoryTree();
                //var_dump($result);
                echo json_encode($result);
            });
            $this->post('/tree', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->setServiceCategoryTree($req->getParsedBody());
                //var_dump($result);
                echo json_encode($result);
            });
        });
        $this->group('/selecttree', function () use ($uiRestHandler) {
            $this->get('/trees', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->getSelectTrees();
                //var_dump($result);
                echo json_encode($result);
            });
            $this->get('/list/{id}', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->getSelectTreeList($args['id']);
                //var_dump($result);
                echo json_encode($result);
            });
            $this->get('/tree/{id}', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->getSelectTree($args['id']);
                //var_dump($result);
                echo json_encode($result);
            });
            $this->post('/tree', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->setSelectTree($req->getParsedBody());
                //var_dump($result);
                echo json_encode($result);
            });
            $this->post('/newtree', function ($req, $res, $args) use ($uiRestHandler) {
                $result = $uiRestHandler->setTree($req->getParsedBody());
                //var_dump($result);
                echo json_encode($result);
            });
        });

        $this->group('/modelvalidations', function () use ($uiRestHandler) {
            $this->get( '', 'modules/SystemUI/KREST/controllers/SystemUIModelValidationsController::getModelValidations' );

            $this->get('/{module}', function ($req, $res, $args) use ($uiRestHandler) {
                echo json_encode($uiRestHandler->getModuleModelValidations($args['module']), JSON_HEX_TAG);
            });

            $this->post('', function ($req, $res, $args) use ($uiRestHandler) {
                //$postbody = json_decode($req->getParsedBody(), true);var_dump($req->getParsedBody(), $postbody, $req->getParams());
                $postbody = $req->getParsedBody();
                echo json_encode($uiRestHandler->setModelValidation($postbody));
            });
            $this->delete('/{id}', function ($req, $res, $args) use ($uiRestHandler) {
                echo json_encode($uiRestHandler->deleteModelValidation($args['id']));
            });
        });
    });
    $this->group('/admin', function () use ($uiRestHandler) {
        $this->get('/navigation', function ($req, $res, $args) use ($uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->getAdminNavigation());
        });
        $this->group('/modules', function () use ($uiRestHandler) {
            $this->get('', function ($req, $res, $args) use ($uiRestHandler) {
                echo json_encode($uiRestHandler->getAllModules());
            });
        });
    });
});
