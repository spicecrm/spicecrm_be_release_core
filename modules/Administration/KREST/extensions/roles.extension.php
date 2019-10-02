<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 02.10.2019
 * Time: 17:08
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