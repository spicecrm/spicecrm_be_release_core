<?php

// require_once('modules/KReports/KReport.php');
require_once('modules/KReports/KReport.php');
require_once('modules/KReports/KReportVisualizationManager.php');
require_once('modules/KReports/KReportPresentationManager.php');
require_once('modules/KReports/KReportRESTHandler.php');
use SpiceCRM\modules\KReports\KREST\controllers\KReportsPluginSavedFiltersController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$RESTManager->registerExtension('reporting', '1.0');

$KReportRestHandler = new KReporterRESTHandler();

$RESTManager->app->group('/KReporter', function () use ($RESTManager, $KReportRestHandler) {
    $this->group('/core', function () use ($RESTManager, $KReportRestHandler) {
        $this->get('/whereinitialize', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->whereInitialize());
        });
        $this->get('/whereoperators', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getWhereOperators($getParams['path'], $getParams['grouping'], $getParams['designer']));
        });
        $this->get('/whereoperators/all', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getAllWhereOperators());
        });
        $this->get('/enumoptions', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getEnumOptions($getParams['path'], $getParams['grouping'], json_decode(html_entity_decode($getParams['operators']), true)));
        });
        $this->get('/nodes', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getNodes($getParams['nodeid']));
        });
        $this->get('/fields', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getFields($getParams['nodeid']));
        });
        $this->get('/buckets', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getBuckets());
        });
        $this->get('/modulefields', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getModuleFields($getParams['module']));
        });
        $this->get('/wherefunctions', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->getWhereFunctions());
        });
        $this->get('/autocompletevalues', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->geAutoCompletevalues($getParams['path'], $getParams['query'], $getParams['start'], $getParams['limit']));
        });
        $this->get('/layouts', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->getLayouts());
        });

        $this->get('/vizcolors', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->getVizColors());
        });
        $this->group('/savelayout', function () use ($RESTManager, $KReportRestHandler) {
            $this->post('', function ($req, $res, $args) use ($KReportRestHandler) {
                $postBody = $req->getParsedBody();
                echo json_encode($KReportRestHandler->saveStandardLayout($postBody['record'], $postBody['layout']));
            });
            $this->post('/{report_id}', function ($req, $res, $args) use ($KReportRestHandler) {
                $postBody = $req->getParsedBody();
                echo json_encode($KReportRestHandler->saveStandardLayout($args['report_id'], $postBody['layout']));
            });
        });
        $this->get('/config', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->getConfig());
        });
        $this->get('/labels', function () use ( $RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->getLabels());
        });
        $this->get('/currencies', function () use ($KReportRestHandler) {
            echo json_encode($KReportRestHandler->getCurrencies());
        });
    });

// Old implementation. Keeping in it in case needed in future KReporter 5.x releases
//    $app->group('/securitygroups', function () use ($app, $KRESTManager, $KReportRestHandler) {
//        $app->post('/save', function ($req, $res, $args) use ($app, $KReportRestHandler) {
//            $postBody = $req->getParsedBody();
//            echo json_encode($KReportRestHandler->saveSecurityGroups($postBody));
//        });
//        $app->get('/get/{report_id}', function($req, $res, $args) use ($app, $KRESTManager, $KReportRestHandler) {
//            $restHandler = new KReporterRESTHandler();
//            echo json_encode($restHandler->getSecurityGroups($args['report_id']));
//        });
//    });



    $this->group('/user', function () use ($RESTManager, $KReportRestHandler) {
        $this->get('/datetimeformat', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->get_user_datetime_format());
        });
        $this->get('/userprefs', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            echo json_encode($restHandler->get_user_prefs());
        });
        $this->get('/getlist', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $params = $_GET;
            echo json_encode($restHandler->get_users_list($params));
        });

    });



    $this->group('/plugins', function () use ($KReportRestHandler) {
        $this->get('', function ($req, $res, $args) use ($KReportRestHandler) {
            $pluginManager = new KReportPluginManager();

            $params = $_GET;
            $pluginData = $pluginManager->getPlugins($params['report']);

            $addDataArray = array();
            if ($params['addData']) {
                $addData = json_decode(html_entity_decode($params['addData']), true);
                foreach ($addData as $addDataEntry) {
                    switch ($addDataEntry) {
                        case 'currencies':
                            $addDataArray[$addDataEntry] = $KReportRestHandler->getCurrencies();
                            break;
                        case 'sysinfo':
                            $addDataArray[$addDataEntry] = $KReportRestHandler->getSysinfo();
                            break;
                    }
                }
            }

            $pluginData['addData'] = $addDataArray;

            echo json_encode($pluginData);
        });

        $this->group('/action/{plugin}/{action}', function() {
            $this->post('', function($req, $res, $args) {
                $pluginManager = new KReportPluginManager();
                $getParams = $_GET;

                //BEGIN BEFORE slim update
                //$formBody = $_POST;
                //$postBody = json_decode($formBody, true);
                //if(!$postBody && $formBody != ''){
                //    parse_str($_POST, $postBody);
                //}
                //END

                //BEGINAFTER slim update
                $formBody = $req->getParsedBody();
                $postBody = $formBody;
                if(!$postBody && $formBody != ''){
                    parse_str($req->getParsedBody(), $postBody);
                }
                //END

                if(!$postBody) $postBody = array();

                //Only return if not null! In case of empty we get a null line in exports (csv, xlsx) and excel can't open file properly
                //echo json_encode($pluginManager->processPluginAction($args['plugin'], 'action_' . $args['action'], array_merge($getParams,$postBody)));
                $resultsPluginAction = $pluginManager->processPluginAction($args['plugin'], 'action_' . $args['action'], array_merge($getParams,$postBody));
                if(!empty($resultsPluginAction))
                    echo json_encode($resultsPluginAction);
            });
            $this->get('', function($req, $res, $args) {
                $pluginManager = new KReportPluginManager();
                $getParams = $_GET;
                echo json_encode($pluginManager->processPluginAction($args['plugin'], 'action_' . $args['action'], $getParams));
            });
        });
    });


    $this->group('/{reportId}', function () use ($RESTManager, $KReportRestHandler) {
        $this->get('', function ($req, $res, $args) use ($RESTManager) {
            $thisReport = BeanFactory::getBean('KReports', $args['reportId']);
            $vizData = json_decode(html_entity_decode($thisReport->visualization_params, ENT_QUOTES, 'UTF-8'), true);
            $pluginManager = new KReportPluginManager();
            $vizObject = $pluginManager->getVisualizationObject('googlecharts');
            echo json_encode($vizObject->getItem('', $thisReport, $vizData[1]['googlecharts']));
        });
        $this->group('/snapshot', function () use ($RESTManager) {
            $this->get('', function ($req, $res, $args) use ($RESTManager) {
                $thisReport = new KReport();
                $thisReport->retrieve($args['reportId']);
                $requestParams = $_GET;
                echo json_encode($thisReport->getSnapshots($requestParams['withoutActual']));
            });
            $this->group('/{snapshotId}', function () use ($RESTManager) {
                $this->delete('', function ($req, $res, $args) use ($RESTManager) {
                    $thisReport = new KReport();
                    $thisReport->retrieve($args['reportId']);
                    $thisReport->deleteSnapshot($args['snapshotId']);
                });
                $this->get('/whereconditions', function ($req, $res, $args) use ($RESTManager) {
                    echo json_encode(KReport::getSnapshotWhereClause($args['snapshotId']));
                });
            });
        });

        if (class_exists('SpiceCRM\modules\KReports\KREST\controllers\KReportsPluginSavedFiltersController')) {
            $this->group('/savedfilter', function () use ($RESTManager, $KReportRestHandler) {
                $this->get('', [new KReportsPluginSavedFiltersController(), 'getSavedFilters']);
                $this->get('/assigneduserid/{assignedUserId}', [new KReportsPluginSavedFiltersController(), 'getSavedFilters']);

                $this->group('/{savedFilterId}', function () use ($RESTManager, $KReportRestHandler) {
                    $this->post('', [new KReportsPluginSavedFiltersController(), 'saveFilter']);
                    $this->delete('', [new KReportsPluginSavedFiltersController(), 'deleteFilter']);
                });
            });
        }

        $this->get('/layout', function($req, $res, $args) use ($RESTManager) {
            $layout = array();
            $thisReport = BeanFactory::getBean('KReports', $args['reportId']);
            $vizData = json_decode(html_entity_decode($thisReport->visualization_params, ENT_QUOTES, 'UTF-8'), true);
            echo(json_encode($vizData));
            $vizManager = new KReportVisualizationManager();

            for ($i = 0; $i < count($vizManager->layouts[$vizData['layout']]['items']); $i++) {
                $layout[] = array(
                    "top" => $vizManager->layouts[$vizData['layout']]['items'][$i]['top'],
                    "left" => $vizManager->layouts[$vizData['layout']]['items'][$i]['left'],
                    "height" => $vizManager->layouts[$vizData['layout']]['items'][$i]['height'],
                    "width" => $vizManager->layouts[$vizData['layout']]['items'][$i]['width']
                );
            }
            // echo json_encode($layout);
        });
//        $app->get('/visualization', function ($args['reportId']) use ($app, $KReportRestHandler) {
//            echo json_encode($KReportRestHandler->getVisualization($args['reportId'], $_GET));
//        });
//        $app->get('/presentation', function ($args['reportId']) use ($app, $KReportRestHandler) {
//            echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $_GET));
//        });

        $this->group('/visualization', function () use ($RESTManager, $KReportRestHandler) {
            $this->post('', function($req, $res, $args) use ($KReportRestHandler) {
                $requestParams = $_GET;
                $postBody = json_decode($req->getParsedBody(), true);
                if(!is_array($requestParams))
                    $requestParams = array();
                if(is_array($postBody))
                    $requestParams = array_merge($requestParams, $postBody);
                echo json_encode($KReportRestHandler->getVisualization($args['reportId'], $requestParams));
            });
            $this->get('', function($req, $res, $args) use ($KReportRestHandler) {
                echo json_encode($KReportRestHandler->getVisualization($args['reportId'], $_GET));
            });
//            $app->group('/dynamicoptions/:dynamicoptions', function ($args['reportId'], $dynamicoptions) use ($app, $KRESTManager, $KReportRestHandler) {
//                $app->get('', function ($args['reportId'], $dynamicoptions) use ($app, $KRESTManager, $KReportRestHandler) {
//
//                    $requestParams = $_GET;
//                    $requestParams['dynamicoptions'] = $dynamicoptions;
//                    echo json_encode($KReportRestHandler->getVisualization($args['reportId'], $requestParams));
//                });
//            });
            //passing dynamicoptions in url may generate a far too long url and trigger a http 400 bad request
            //pass dynamicoptions to post
            $this->group('/dynamicoptions', function () use ($RESTManager, $KReportRestHandler) {
                $this->post('', function($req, $res, $args) use ($RESTManager, $KReportRestHandler) {
                    $requestParams = $_GET;
                    $postBody = $req->getParsedBody();
                    if(!is_array($requestParams))
                        $requestParams = array();
                    if(is_array($postBody))
                        $requestParams = array_merge($requestParams, $postBody);
//                    if(isset($postBody['dynamicoptions']))
//                        $requestParams['dynamicoptions'] = $postBody['dynamicoptions'];
//                    if(isset($postBody['whereConditions']))
//                        $requestParams['whereConditions'] = $postBody['whereConditions'];
                    echo json_encode($KReportRestHandler->getVisualization($args['reportId'], $requestParams));
                });
                //keep a get for UI and Sugar7....
                $this->get('', function($req, $res, $args) use ($RESTManager, $KReportRestHandler) {
                    $requestParams = $_GET;
                    $postBody = $req->getParsedBody();
//                    if(isset($postBody['dynamicoptions']))
//                        $requestParams['dynamicoptions'] = $postBody['dynamicoptions'];
//                    if(isset($postBody['whereConditions']))
//                        $requestParams['whereConditions'] = $postBody['whereConditions'];
                    if(!is_array($requestParams))
                        $requestParams = array();
                    if(is_array($postBody))
                        $requestParams = array_merge($requestParams, $postBody);
                    echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $requestParams));
                });
            });
        });

        $this->group('/presentation', function () use ($RESTManager, $KReportRestHandler) {
            $this->post('', function($req, $res, $args) use ($KReportRestHandler) {
                $requestParams = $_GET;
                $postBody = $req->getParsedBody();
                if(!is_array($requestParams))
                    $requestParams = array();
                if(is_array($postBody))
                    $requestParams = array_merge($requestParams, $postBody);
                echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $requestParams));
            });
            $this->get('', function($req, $res, $args) use ($KReportRestHandler) {
                echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $_GET));
            });
//            $app->group('/dynamicoptions/:dynamicoptions', function ($args['reportId'], $dynamicoptions) use ($app, $KRESTManager, $KReportRestHandler) {
//                $app->get('', function ($args['reportId'], $dynamicoptions) use ($app, $KRESTManager, $KReportRestHandler) {
//                    $requestParams = $_GET;
//                    $requestParams['dynamicoptions'] = $dynamicoptions;
//                    echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $requestParams));
//                });
//            });
            //passing dynamicoptions in url may generate a far too long url and trigger a http 400 bad request
            //pass dynamicoptions to post
            $this->group('/dynamicoptions', function () use ($RESTManager, $KReportRestHandler) {
                $this->post('', function($req, $res, $args) use ($RESTManager, $KReportRestHandler) {
                    $requestParams = $_GET;
                    $postBody = $req->getParsedBody();
//                    if(isset($postBody['dynamicoptions']))
//                        $requestParams['dynamicoptions'] = $postBody['dynamicoptions'];
//                    if(isset($postBody['whereConditions']))
//                        $requestParams['whereConditions'] = $postBody['whereConditions'];
                    if(!is_array($requestParams))
                        $requestParams = array();
                    if(is_array($postBody))
                        $requestParams = array_merge($requestParams, $postBody);
                    echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $requestParams));
                });
                //keep a get for UI and Sugar7....
                $this->get('', function($req, $res, $args) use ($RESTManager, $KReportRestHandler) {
                    $requestParams = $_GET;
                    $postBody = $req->getParsedBody();
                    if(!is_array($requestParams))
                        $requestParams = array();
                    if(is_array($postBody))
                        $requestParams = array_merge($requestParams, $postBody);
                    echo json_encode($KReportRestHandler->getPresentation($args['reportId'], $requestParams));
                });
            });


        });
    });


    $this->group('/bucketmanager', function () use ($RESTManager, $KReportRestHandler) {
        $this->get('/enumfields', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getEnumfields($getParams['modulename']));
        });
        $this->get('/enumfieldvalues', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getEnumfieldvalues($getParams));
        });
        $this->get('/groupings', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getGroupings());
        });

        $this->post('/savenewgrouping', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->saveNewGrouping($postBody));
        });
        $this->post('/updateGrouping', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->updateGrouping($postBody));
        });
        $this->post('/deleteGrouping', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->deleteGrouping($postBody));
        });
    });

    $this->group('/dlistmanager', function () use ($RESTManager, $KReportRestHandler) {
        $this->get('/dlists', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getDLists());
        });
        $this->get('/users', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getUsers($getParams));
        });
        $this->get('/contacts', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getContacts($getParams));
        });
        $this->get('/kreports', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getKReports($getParams));
        });

        $this->post('/savenewdlist', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->saveNewDList($postBody));
        });
        $this->post('/updatedlist', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->updateDList($postBody));
        });
        $this->post('/deletedlist', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->deleteDList($postBody));
        });
    });

    //KReporter Cockpit VIew
    $this->group('/categoriesmanager', function () use ($RESTManager, $KReportRestHandler) {
        $this->get('/categories', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getCategories($getParams));
        });
        $this->get('/cockpit', function () use ($RESTManager) {
            $restHandler = new KReporterRESTHandler();
            $getParams = $_GET;
            echo json_encode($restHandler->getCockpit());
        });
        $this->post('/savenewcategory', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->saveNewCategory($postBody));
        });
        $this->post('/updatecategory', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->updateCategory($postBody));
        });
        $this->post('/deletecategory', function ($req, $res, $args) use ($KReportRestHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KReportRestHandler->deleteCategory($postBody));
        });
    });


});
