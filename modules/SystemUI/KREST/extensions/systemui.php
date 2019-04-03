<?php

$uiRestHandler = new SpiceCRM\modules\SystemUI\SystemUIRESTHandler();

$app->group('/spiceui', function () use ($app, $uiRestHandler) {
    $app->group('/core', function () use ($app, $uiRestHandler) {
        /**
         * handle the load tasks
         */
        $this->group('/loadtasks', function () use ($app, $uiRestHandler) {
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
         * get html styles
         */
        $app->get('/htmlstyling', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIHTMLStylesheetsController::getHtmlStyleSheets');

        /**
         * all module related calls
         */
        $app->group('/modules', function () use ($app, $uiRestHandler) {
            /*
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                echo json_encode(array(
                    'modules' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModulesController::getModules(),
                    'roles' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModulesController::getSysRoles(),
                    'rolemodules' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModulesController::getSysRoleModules(),
                    'copyrules' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModulesController::getSysCopyRules(),
                    'modulefilters' => \SpiceCRM\includes\SysModuleFilters\SysModuleFilters::getAllModuleFilters(),
                    'validationrules' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModelValidationsController::getAllModelValidations()
                ));
            });
            */
            $app->group('/{module}/listtypes', function () use ($app, $uiRestHandler) {
                $app->post('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                    $postbody = $req->getParsedBody();
                    echo json_encode($uiRestHandler->addListType($args['module'], $postbody['list'], $postbody['global']));
                });
                $app->post('/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                    $postbody = $req->getParsedBody();
                    echo $uiRestHandler->setListType($args['id'], $postbody);
                });
                $app->delete('/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                    echo $uiRestHandler->deleteListType($args['id']);
                });
            });
        });

        /**
         * @deprecated
         *
         * to be removed
         */
        $app->get('/components', function ($req, $res, $args) use ($app, $uiRestHandler) {
            echo json_encode(array(
                'modules' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRepositoryController::getModuleRepository(),
                'components' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRepositoryController::getComponents(),
                'componentdefaultconfigs' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRepositoryController::getComponentDefaultConfigs(),
                'componentmoduleconfigs' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRepositoryController::getComponentModuleConfigs(),
                'componentsets' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIComponentsetsController::getComponentSets(),
                'actionsets' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIActionsetsController::getActionSets(),
                'routes' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRoutesController::getRoutesDirect(),
                'scripts' => \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRepositoryController::getLibraries(),
            ));
        });


        $app->get('/componentmodulealreadyexists', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->checkComponentModuleAlreadyExists($getParams));
        });
        $app->get('/componentdefaultalreadyexists', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->checkComponentDefaultAlreadyExists($getParams));
        });

        $app->post('/componentsets', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $postbody = $req->getParsedBody();
            echo json_encode($uiRestHandler->setComponentSets($postbody));
        });
        $app->group('/fieldsets', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) {
                $res->write(json_encode(['fieldsets' => SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIFieldsetsController::getFieldSets()]));
            });
            $app->post('', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIFieldsetsController::setFieldSets');
        });
        $app->get('/fieldsetalreadyexists', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->checkFieldSetAlreadyExists($getParams));
        });
        $app->get('/fielddefs', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->getFieldDefs(json_decode($getParams['modules'])));
        });
        $app->get('/routes', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIRoutesController::getRoutes');
        /*
        $app->get('/recent', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->getRecent($getParams['module'], $getParams['limit']));
        });
        */
        /*
        $app->group('/favorites', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                echo json_encode($uiRestHandler->getFavorites());
            });
            $app->post('/{module}/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                \SpiceCRM\includes\SpiceFavorites\SpiceFavorites::set_favorite($args['module'], $args['id']);
                $bean = \BeanFactory::getBean($args['module'], $args['id']);
                echo json_encode(array(
                    'module' => $args['module'],
                    'id' => $args['id'],
                    'summary_text' => $bean->get_summary_text()
                ));
            });
            $app->delete('/{module}/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                \SpiceCRM\includes\SpiceFavorites\SpiceFavorites::delete_favorite($args['module'], $args['id']);
                echo json_encode(array('status' => 'success'));
            });
        });
        */
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
        /*
        $app->group('/reminders', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $getParams = $req->getParams();
                echo json_encode($uiRestHandler->getReminders());
            });
            $app->post('/{module}/{id}/{date}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                \SpiceCRM\includes\SpiceReminders\SpiceReminders::setReminderRaw($args['id'], $args['module'], $args['date']);
                echo json_encode(array('status' => 'success'));
            });
            $app->delete('/{module}/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                \SpiceCRM\includes\SpiceReminders\SpiceReminders::removeReminder($args['id']);
                echo json_encode(array('status' => 'success'));
            });
        });
        */
        $app->group('/modelvalidations', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                //var_dump($uiRestHandler->getAllModelValidations());
                echo json_encode($uiRestHandler->getAllModelValidations());
            });
            $app->get('/{module}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                echo json_encode($uiRestHandler->getModuleModelValidations($args['module']), JSON_HEX_TAG);
            });
            $app->post('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                //$postbody = json_decode($req->getParsedBody(), true);var_dump($req->getParsedBody(), $postbody, $req->getParams());
                $postbody = $req->getParsedBody();
                echo json_encode($uiRestHandler->setModelValidation($postbody));
            });
            $app->delete('/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                echo json_encode($uiRestHandler->deleteModelValidation($args['id']));
            });
        });
        $app->group('/libraries', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                echo json_encode($uiRestHandler->getLibraries());
            });
        });
        $app->group('/servicecategories', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->getServiceCategories();
                //var_dump($result);
                echo json_encode($result);
            });
            $app->get('/tree', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->getServiceCategoryTree();
                //var_dump($result);
                echo json_encode($result);
            });
            $app->post('/tree', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->setServiceCategoryTree($req->getParsedBody());
                //var_dump($result);
                echo json_encode($result);
            });
        });
        $app->group('/selecttree', function () use ($app, $uiRestHandler) {
            $app->get('/trees', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->getSelectTrees();
                //var_dump($result);
                echo json_encode($result);
            });
            $app->get('/list/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->getSelectTreeList($args['id']);
                //var_dump($result);
                echo json_encode($result);
            });
            $app->get('/tree/{id}', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->getSelectTree($args['id']);
                //var_dump($result);
                echo json_encode($result);
            });
            $app->post('/tree', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->setSelectTree($req->getParsedBody());
                //var_dump($result);
                echo json_encode($result);
            });
            $app->post('/newtree', function ($req, $res, $args) use ($app, $uiRestHandler) {
                $result = $uiRestHandler->setTree($req->getParsedBody());
                //var_dump($result);
                echo json_encode($result);
            });
        });
    });
    $app->group('/admin', function () use ($app, $uiRestHandler) {
        $app->get('/navigation', function ($req, $res, $args) use ($app, $uiRestHandler) {
            $getParams = $req->getParams();
            echo json_encode($uiRestHandler->getAdminNavigation());
        });
        $app->group('/modules', function () use ($app, $uiRestHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $uiRestHandler) {
                echo json_encode($uiRestHandler->getAllModules());
            });
        });
    });
    /*
    $app->group('/workbench', function($req, $res, $args) use ($app, $uiRestHandler) {

    });
    */
});