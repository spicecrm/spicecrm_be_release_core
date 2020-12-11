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
$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('module', '2.0');

$restapp = $RESTManager->app;

$ModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler($restapp);


$restapp->group('/module', function () use ($restapp, $RESTManager, $ModuleHandler) {

    $restapp->group('/{beanName}', function () use ($restapp, $ModuleHandler) {
        $restapp->get('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
            $searchParams = $_GET;
            echo json_encode($ModuleHandler->get_bean_list($args['beanName'], $searchParams));
        });

        $restapp->post('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
            $requestParams = $_GET;
            $retArray = array();

            if (!$GLOBALS['ACLController']->checkAccess($args['beanName'], 'edit', true) && !$GLOBALS['ACLController']->checkAccess($args['beanName'], 'create', true)){
                throw (new \SpiceCRM\includes\ErrorHandlers\ForbiddenException('Forbidden to edit in module ' . $args['beanName'] . '.'))->setErrorCode('noModuleEdit');
            }

            $items = $req->getParsedBody();

            if (!is_array($items) || !is_array($items[0]))
                throw new \SpiceCRM\includes\ErrorHandlers\BadRequestException('Reading Body failed. An Array with at least one Object is expected: [{object1},{object2},...]');

            foreach ($items as $item) {
                if (!is_array($item))
                    continue;

                $args['beanId'] = $ModuleHandler->add_bean($args['beanName'], $item['id'], array_merge($item, $requestParams));
                $item['data'] = $args['beanId'];
                $retArray[] = $item;
            }

            //echo json_encode($retArray);
            return $res->withJson($retArray);
        });
        $restapp->post('/export', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
            $searchParams = $req->getParsedBody();
            $charset = $ModuleHandler->export_bean_list($args['beanName'], $searchParams);
            return $res->withHeader('Content-Type', 'text/csv; charset=' . $charset);
        });
        $restapp->post('/duplicates', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($ModuleHandler->check_bean_duplicates($args['beanName'], $postBody));
        });
        $restapp->delete('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
            foreach ($req->getParsedBodyParam('ids') as $id) {
                $response[$id] = $ModuleHandler->delete_bean($args['beanName'], $id);
            }
            return $res->withJson($response);
        });
        $restapp->group('/{beanId}', function () use ($restapp, $ModuleHandler) {
            $restapp->get('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                $requestParams = $_GET;
                echo json_encode($ModuleHandler->get_bean_detail($args['beanName'], $args['beanId'], $requestParams));
            });
            $restapp->post('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                $params = $req->getParsedBody();

                $req->getBody()->rewind();
                if ($req->getBody()->getContents() === '') { # and $req->getContentType() === 'application/json'
                    throw (new \SpiceCRM\includes\ErrorHandlers\BadRequestException('Request has empty content. Retry?! Or contact the administrator!'))->setFatal(true);
                }

                $bean = $ModuleHandler->add_bean($args['beanName'], $args['beanId'], $params, $req->getQueryParams());
                echo json_encode($bean);
            });
            $restapp->delete('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                return $ModuleHandler->delete_bean($args['beanName'], $args['beanId']);
            });
            $restapp->get('/duplicates', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                echo json_encode($ModuleHandler->get_bean_duplicates($args['beanName'], $args['beanId']));
            });
            $restapp->get('/auditlog', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                $params = $req->getParams();
                echo json_encode($ModuleHandler->get_bean_auditlog($args['beanName'], $args['beanId'], $params));
            });
            $restapp->group('/noteattachment', function () use ($restapp, $ModuleHandler) {
                $restapp->get('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    echo json_encode($ModuleHandler->get_bean_attachment($args['beanName'], $args['beanId']));
                });
                $restapp->get('/download', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    echo json_encode($ModuleHandler->download_bean_attachment($args['beanName'], $args['beanId']));
                });
                $restapp->post('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($ModuleHandler->set_bean_attachment($args['beanName'], $args['beanId'], $postBody));
                });
            });
            $restapp->group('/checklist', function () use ($restapp, $ModuleHandler) {
                $restapp->post('/{fieldname}/{item}', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $fieldname = $args['fieldname'];
                    $item = $args['item'];
                    $bean = BeanFactory::getBean($args['beanName'], $args['beanId'], ['encode' => false]);
                    if ($bean->id == $args['beanId']) {
                        $values = json_decode($bean->$fieldname, true);
                        if (!is_array($values)) {
                            $values = array();
                        }
                        $values[$item] = true;
                        $bean->{$fieldname} = json_encode($values);
                        $bean->save();
                        echo json_encode(array('status' => 'success'));
                        return;
                    }
                    echo json_encode(array('status' => 'error'));
                });
                $restapp->delete('/{fieldname}/{item}', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $fieldname = $args['fieldname'];
                    $item = $args['item'];
                    $bean = BeanFactory::getBean($args['beanName'], $args['beanId'], ['encode' => false]);
                    if ($bean->id == $args['beanId']) {
                        $values = json_decode($bean->$fieldname, true);
                        if (!is_array($values)) {
                            $values = array();
                        }
                        unset($values[$item]);
                        $bean->{$fieldname} = json_encode($values);
                        $bean->save();
                        echo json_encode(array('status' => 'success'));
                        return;
                    }
                    echo json_encode(array('status' => 'error'));
                });
            });
            $restapp->group('/related/{linkname}', function () use ($restapp, $ModuleHandler) {
                $restapp->get('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $getParams = $_GET;
                    echo json_encode($ModuleHandler->get_related($args['beanName'], $args['beanId'], $args['linkname'], $getParams));
                });
                $restapp->post('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($ModuleHandler->add_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
                $restapp->put('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($ModuleHandler->set_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
                $restapp->delete('', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                    $params = $req->getParams();
                    echo json_encode($ModuleHandler->delete_related($args['beanName'], $args['beanId'], $args['linkname'], $params));
                });
            });
            $restapp->post('/merge_bean', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                $postBody = $body = $req->getParsedBody();
                $postParams = $_GET;
                $actionData = $ModuleHandler->merge_bean($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
                if ($actionData === false)
                    $restapp->response()->status(501);
                else {
                    echo json_encode($actionData);
                }
            });
            $restapp->post('/{beanAction}', function ($req, $res, $args) use ($restapp, $ModuleHandler) {
                $postBody = $body = $req->getParsedBody();
                $postParams = $_GET;
                $actionData = $ModuleHandler->execute_bean_action($args['beanName'], $args['beanId'], $args['beanAction'], array_merge($postBody, $postParams));
                if ($actionData === false)
                    $restapp->response()->status(501);
                else {
                    echo json_encode($actionData);
                }
            });
        });
    })->add(function ($request, $response, $next) {
        $beanName = $request->getAttribute('route')->getArgument('beanName');
        if (method_exists('BeanFactory', 'moduleExists') && !BeanFactory::moduleExists($beanName)) {
            throw (new \SpiceCRM\includes\ErrorHandlers\NotFoundException('Module not found.'))->setLookedFor(['module' => $beanName])->setErrorCode('noModule');
        }
        return $next($request, $response);
    });
});

$restapp->post('/bean/file/upload', [new \SpiceCRM\KREST\handlers\ModuleHandler($restapp), 'uploadFile']);
