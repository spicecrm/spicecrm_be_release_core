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


$ModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler($app);

$KRESTManager->registerExtension('module', '2.0');

$app->group('/module', function () use ($app, $KRESTManager, $ModuleHandler) {

    $app->get('/language', function ($req, $res, $args) use ($app, $ModuleHandler) {
        return $res->withJson($ModuleHandler->getLanguage(json_decode($req->getQueryParam('modules')), $req->getQueryParam('lang')));
    });

    $app->post('/language', function ($req, $res, $args) use ($app, $ModuleHandler) {
        $KRESTUserHandler = new \SpiceCRM\KREST\handlers\UserHandler();
        $KRESTUserHandler->set_user_preferences('global', ['language' => $req->getQueryParam('lang')]);
        return $res->withJson($ModuleHandler->getLanguage(json_decode($req->getQueryParam('modules')), $req->getQueryParam('lang')));
    });

    $app->group('/{beanName}', function () use ($app, $ModuleHandler) {
        $app->get('', function ($req, $res, $args) use ($app, $ModuleHandler) {
            $searchParams = $_GET;
            echo json_encode($ModuleHandler->get_bean_list($args['beanName'], $searchParams));
        });

        $app->post('', function ($req, $res, $args) use ($app, $ModuleHandler) {
            $requestParams = $_GET;
            $retArray = array();

            if (!$GLOBALS['ACLController']->checkAccess($args['beanName'], 'edit', true) && !$GLOBALS['ACLController']->checkAccess($args['beanName'], 'create', true)){
                throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to edit in module ' . $args['beanName'] . '.'))->setErrorCode('noModuleEdit');
            }

            $items = $req->getParsedBody();

            if (!is_array($items) || !is_array($items[0]))
                throw new \SpiceCRM\KREST\BadRequestException('Reading Body failed. An Array with at least one Object is expected: [{object1},{object2},...]');

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
        $app->post('/export', function ($req, $res, $args) use ($app, $ModuleHandler) {
            $searchParams = $req->getParsedBody();
            $charset = $ModuleHandler->export_bean_list($args['beanName'], $searchParams);
            return $res->withHeader('Content-Type', 'text/csv; charset=' . $charset);
        });
        $app->post('/duplicates', function ($req, $res, $args) use ($app, $ModuleHandler) {
            $postBody = $req->getParsedBody();
            echo json_encode($ModuleHandler->check_bean_duplicates($args['beanName'], $postBody));
        });
        $app->delete('', function ($req, $res, $args) use ($app, $ModuleHandler) {
            foreach ($req->getParsedBodyParam('ids') as $id) {
                $response[$id] = $ModuleHandler->delete_bean($args['beanName'], $id);
            }
            return $res->withJson($response);
        });
        $app->group('/{beanId}', function () use ($app, $ModuleHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                $requestParams = $_GET;
                echo json_encode($ModuleHandler->get_bean_detail($args['beanName'], $args['beanId'], $requestParams));
            });
            $app->post('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                $params = $req->getParsedBody();

                $req->getBody()->rewind();
                if ($req->getBody()->getContents() === '') { # and $req->getContentType() === 'application/json'
                    throw (new \SpiceCRM\KREST\BadRequestException('Request has empty content. Retry?! Or contact the administrator!'))->setFatal(true);
                }

                $bean = $ModuleHandler->add_bean($args['beanName'], $args['beanId'], $params, $req->getQueryParams());
                echo json_encode($bean);
            });
            $app->delete('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                return $ModuleHandler->delete_bean($args['beanName'], $args['beanId']);
            });
            $app->get('/duplicates', function ($req, $res, $args) use ($app, $ModuleHandler) {
                echo json_encode($ModuleHandler->get_bean_duplicates($args['beanName'], $args['beanId']));
            });
            $app->get('/auditlog', function ($req, $res, $args) use ($app, $ModuleHandler) {
                $params = $req->getParams();
                echo json_encode($ModuleHandler->get_bean_auditlog($args['beanName'], $args['beanId'], $params));
            });
            $app->group('/noteattachment', function () use ($app, $ModuleHandler) {
                $app->get('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    echo json_encode($ModuleHandler->get_bean_attachment($args['beanName'], $args['beanId']));
                });
                $app->get('/download', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    echo json_encode($ModuleHandler->download_bean_attachment($args['beanName'], $args['beanId']));
                });
                $app->post('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($ModuleHandler->set_bean_attachment($args['beanName'], $args['beanId'], $postBody));
                });
            });
            $app->group('/checklist', function () use ($app, $ModuleHandler) {
                $app->post('/{fieldname}/{item}', function ($req, $res, $args) use ($app, $ModuleHandler) {
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
                $app->delete('/{fieldname}/{item}', function ($req, $res, $args) use ($app, $ModuleHandler) {
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
            $app->group('/related/{linkname}', function () use ($app, $ModuleHandler) {
                $app->get('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    $getParams = $_GET;
                    echo json_encode($ModuleHandler->get_related($args['beanName'], $args['beanId'], $args['linkname'], $getParams));
                });
                $app->post('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($ModuleHandler->add_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
                $app->put('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    $postBody = $req->getParsedBody();
                    echo json_encode($ModuleHandler->set_related($args['beanName'], $args['beanId'], $args['linkname'], $postBody));
                });
                $app->delete('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    $params = $req->getParams();
                    echo json_encode($ModuleHandler->delete_related($args['beanName'], $args['beanId'], $args['linkname'], $params));
                });
            });
            $app->group('/filtered', function () use ($app, $ModuleHandler) {
                $app->get('', function ($req, $res, $args) use ($app, $ModuleHandler) {
                    $getParams = $_GET;
                    echo json_encode($ModuleHandler->get_filtered($args['beanName'], $args['beanId'], $getParams));
                });
            });
            $app->post('/merge_bean', function ($req, $res, $args) use ($app, $ModuleHandler) {
                $postBody = $body = $req->getParsedBody();
                $postParams = $_GET;
                $actionData = $ModuleHandler->merge_bean($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
                if ($actionData === false)
                    $app->response()->status(501);
                else {
                    echo json_encode($actionData);
                }
            });
            $app->post('/{beanAction}', function ($req, $res, $args) use ($app, $ModuleHandler) {
                $postBody = $body = $req->getParsedBody();
                $postParams = $_GET;
                $actionData = $ModuleHandler->execute_bean_action($args['beanName'], $args['beanId'], $args['beanAction'], array_merge($postBody, $postParams));
                if ($actionData === false)
                    $app->response()->status(501);
                else {
                    echo json_encode($actionData);
                }
            });
        });
    })->add(function ($request, $response, $next) {
        $beanName = $request->getAttribute('route')->getArgument('beanName');
        if (method_exists('BeanFactory', 'moduleExists') && !BeanFactory::moduleExists($beanName)) {
            throw (new \SpiceCRM\KREST\NotFoundException('Module not found.'))->setLookedFor(['module' => $beanName])->setErrorCode('noModule');
        }
        return $next($request, $response);
    });
});
