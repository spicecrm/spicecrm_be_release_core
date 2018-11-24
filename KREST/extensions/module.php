<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

require_once('KREST/handlers/module.php');

$KRESTModuleHandler = new KRESTModuleHandler($app);

$KRESTManager->registerExtension('module', '2.0');

$app->group('/module', function () use ($app, $KRESTManager, $KRESTModuleHandler) {

    $app->get('/language', function ($req, $res, $args) use ($app, $KRESTModuleHandler) {
        return $res->withJson( $KRESTModuleHandler->getLanguage( json_decode( $req->getQueryParam('modules')), $req->getQueryParam('lang') ));
    });

    $app->post('/language', function ($req, $res, $args) use ($app, $KRESTModuleHandler) {
        $KRESTUserHandler = new KRESTUserHandler();
        $KRESTUserHandler->set_user_preferences('global', [ 'language' => $req->getQueryParam('lang') ]);
        return $res->withJson( $KRESTModuleHandler->getLanguage( json_decode( $req->getQueryParam('modules')), $req->getQueryParam('lang') ));
    });

    $app->get('/{beanName}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
        $searchParams = $_GET;
        echo json_encode($KRESTModuleHandler->get_bean_list($args['beanName'], $searchParams));
    });

    $app->post('/{beanName}', function($req, $res, $args) use ($app, $KRESTManager, $KRESTModuleHandler) {
        $requestParams = $_GET;
        $retArray = array();

        # // acl check
        # $seed = BeanFactory::getBean($args['beanName']);
        # if(!$seed){
        #     $KRESTManager->authenticationError('no access to module ' . $args['beanName']);
        # }
        # Commented out by Andi because: Creating a bean doesn´t check access rights. And code not necessary, because of following lines (checkAccess).

        if(!ACLController::checkAccess($args['beanName'], 'edit', true))
            throw ( new KREST\ForbiddenException('Forbidden to edit in module '.$args['beanName'].'.'))->setErrorCode('noModuleEdit');

        $items = $req->getParsedBody();

        if (!is_array($items) || !is_array($items[0]))
            throw new KREST\BadRequestException('Reading Body failed. An Array with at least one Object is expected: [{object1},{object2},...]');

        foreach($items as $item)
        {
            if(!is_array($item))
                continue;

            $args['beanId'] = $KRESTModuleHandler->add_bean($args['beanName'], $item['id'], array_merge($item, $requestParams));
            $item['data'] = $args['beanId'];
            $retArray[] = $item;
        }

        //echo json_encode($retArray);
        return $res->withJson($retArray);
    });


    $app->group('/{beanName}', function() use ($app, $KRESTModuleHandler) {
        $app->get('/export', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
            $searchParams = $_GET;
            $KRESTModuleHandler->export_bean_list($args['beanName'], $searchParams);
        });
        $app->post('/duplicates', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($KRESTModuleHandler->check_bean_duplicates($args['beanName'], $postBody));
        });
        $app->get('/{beanId}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
            $requestParams = $_GET;
            echo json_encode($KRESTModuleHandler->get_bean_detail($args['beanName'], $args['beanId'], $requestParams));
        });
        $app->post('/{beanId}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
            /*
            $postBody = $body = $req->getParsedBody();
            $postParams = $_GET;
            $params = array_merge(json_decode($postBody, true), $postParams);
            */
            $params = $req->getParams();
            //var_dump($req->getParsedBody(), $params, $req->getParsedBody(), $req->getParams());
            $bean = $KRESTModuleHandler->add_bean($args['beanName'], $args['beanId'], $params);
            echo json_encode($bean);
        });
        $app->delete('/{beanId}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
            return $KRESTModuleHandler->delete_bean($args['beanName'], $args['beanId']);
        });
        $app->delete('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
            foreach ( $req->getParsedBodyParam('ids') as $id ) {
                $response[$id] = $KRESTModuleHandler->delete_bean($args['beanName'], $id);
            }
            return $res->withJson($response);
        });
        $app->group('/{beanId}', function() use ($app, $KRESTModuleHandler) {
            $app->get('/duplicates', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                echo json_encode($KRESTModuleHandler->get_bean_duplicates($args['beanName'], $args['beanId']));
            });
            $app->get('/auditlog', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                $params = $req->getParams();
                echo json_encode($KRESTModuleHandler->get_bean_auditlog($args['beanName'], $args['beanId'], $params));
            });
            $app->group('/noteattachment', function () use ($app, $KRESTModuleHandler) {
                $app->get('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    echo json_encode($KRESTModuleHandler->get_bean_attachment($args['beanName'], $args['beanId']));
                });
                $app->get('/download', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    echo json_encode($KRESTModuleHandler->download_bean_attachment($args['beanName'], $args['beanId']));
                });
                $app->post('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    echo json_encode($KRESTModuleHandler->set_bean_attachment($args['beanName'], $args['beanId']));
                });
            });
            $app->group('/attachment', function () use ($app) {
                $app->post('', function($req, $res, $args) use ($app) {
                    $postBody = $body = $req->getParsedBody();
                    $postParams = $_GET;
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::saveAttachmentHashFiles($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
                });
                $app->get('', function($req, $res, $args) use ($app) {
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::getAttachmentsForBeanHashFiles($args['beanName'], $args['beanId']);
                });
                $app->delete('/{attachmentId}', function($req, $res, $args) use ($app) {
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::deleteAttachment($args['attachmentId']);
                });
                $app->post('/ui', function($req, $res, $args) use ($app) {
                    /* for fielupload over $_FILE. used by theme */
                    $postBody = $body = $req->getParsedBody();
                    $postParams = $_GET;
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::saveAttachment($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
                });
                $app->get('/ui', function($req, $res, $args) use ($app) {
                    /* for get file url for theme, not file in base64 */
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::getAttachmentsForBean($args['beanName'], $args['beanId']);
                });
                $app->get('/{attachmentId}', function($req, $res, $args) use ($app) {
                    /* for get file url for theme, not file in base64 */
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::getAttachment($args['attachmentId']);
                });
                $app->get('/{attachmentId}/download', function($req, $res, $args) use ($app) {
                    /* for get file url for theme, not file in base64 */
                    require_once('include/SpiceAttachments/SpiceAttachments.php');
                    echo SpiceAttachments::downloadAttachment($args['attachmentId']);
                });
            });
            $app->group('/checklist', function () use ($app, $KRESTModuleHandler) {
                $app->post('/{fieldname}/{item}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $fieldname = $args['fieldname']; $item = $args['item'];
                    $bean = BeanFactory::getBean( $args['beanName'], $args['beanId'], ['encode'=>false] ) ;
                    if($bean->id == $args['beanId']){
                        $values = json_decode($bean->$fieldname, true);
                        if( !is_array( $values )){
                            $values = array();
                        }
                        $values[$item] = true;
                        $bean->{$fieldname} = json_encode($values);
                        $bean->save();
                        echo json_encode(array('status' =>'success'));
                        return;
                    }
                    echo json_encode(array('status' =>'error'));
                });
                $app->delete('/{fieldname}/{item}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $fieldname = $args['fieldname']; $item = $args['item'];
                    $bean = BeanFactory::getBean($args['beanName'], $args['beanId'], ['encode'=>false] );
                    if($bean->id == $args['beanId']){
                        $values = json_decode($bean->$fieldname, true);
                        if(!is_array($values)){
                            $values = array();
                        }
                        unset( $values[$item] );
                        $bean->{$fieldname} = json_encode($values);
                        $bean->save();
                        echo json_encode(array('status' =>'success'));
                        return;
                    }
                    echo json_encode(array('status' =>'error'));
                });
            });
            $app->group('/favorite', function () use ($app, $KRESTModuleHandler) {
                $app->get('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $actionData = $KRESTModuleHandler->get_favorite($args['beanName'], $args['beanId']);
                    echo json_encode($actionData);
                });
                $app->post('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $actionData = $KRESTModuleHandler->set_favorite($args['beanName']);
                });
                $app->delete('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $actionData = $KRESTModuleHandler->delete_favorite($args['beanName']);
                });
            });
            $app->group('/note', function () use ($app) {
                $app->get('', function($req, $res, $args) use ($app) {
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->getQuickNotes($args['beanName'], $args['beanId']);
                });
                $app->post('', function($req, $res, $args) use ($app) {
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $postBody = $body = $req->getParsedBody();
                    $postParams = $_GET;
                    $data = array_merge($postBody, $postParams);
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->saveQuickNote($args['beanName'], $args['beanId'], $data);
                });
                $app->post('/{noteId}', function($req, $res, $args) use ($app) {
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $postBody = $body = $req->getParsedBody();
                    $postParams = $_GET;
                    $data = array_merge($postBody, $postParams);
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->editQuickNote($args['beanName'], $args['beanId'], $args['noteId'], $data);
                });
                $app->delete('/{noteId}', function($req, $res, $args) use ($app) {
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->deleteQuickNote($args['noteId']);
                });
            });
            $app->group('/reminder', function () use ($app) {
                $app->get('', function($req, $res, $args) use ($app) {
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->getReminder();
                });
                $app->post('', function($req, $res, $args) use ($app) {
                    $postBody = $body = $req->getParsedBody();
                    $postParams = $_GET;
                    $data = array_merge($postBody, $postParams);
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->setReminder($args['beanName'], $args['beanId'], $data);
                });
                $app->delete('', function($req, $res, $args) use ($app) {
                    require_once('modules/SpiceThemeController/SpiceThemeController.php');
                    $SpiceThemeController = new SpiceThemeController();
                    echo $SpiceThemeController->removeReminder($args['beanName'], $args['beanId']);
                });
            });

            $app->group('/related/{linkname}', function () use ($app, $KRESTModuleHandler) {
                $app->get('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $getParams = $_GET;
                    echo json_encode($KRESTModuleHandler->get_related($args['beanName'], $args['beanId'], $args['linkname'], $getParams));
                });
                $app->post('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($KRESTModuleHandler->add_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
                $app->put('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($KRESTModuleHandler->set_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
                $app->delete('', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($KRESTModuleHandler->delete_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
            });
            $app->post('/merge_bean', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                $postBody = $body = $req->getParsedBody();
                $postParams = $_GET;
                $actionData = $KRESTModuleHandler->merge_bean($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
                if ($actionData === false)
                    $app->response()->status(501);
                else {
                    echo json_encode($actionData);
                }
            });
            $app->post('/{beanAction}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
                $postBody = $body = $req->getParsedBody();
                $postParams = $_GET;
                $actionData = $KRESTModuleHandler->execute_bean_action($args['beanName'], $args['beanId'], $args['beanAction'], array_merge($postBody, $postParams));
                if ($actionData === false)
                    $app->response()->status(501);
                else {
                    echo json_encode($actionData);
                }
            });
        });
    })->add( function( $request, $response, $next ) {
        $beanName = $request->getAttribute('route')->getArgument('beanName');
        if ( method_exists('BeanFactory', 'moduleExists') && !BeanFactory::moduleExists( $beanName )) {
            throw (new KREST\NotFoundException('Module not found.'))->setLookedFor(['module' => $beanName])->setErrorCode('noModule');
        }
        return $next( $request, $response );
    });
});
