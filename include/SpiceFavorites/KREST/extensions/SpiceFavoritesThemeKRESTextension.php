<?php
$ModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler($app);
$app->group('/module/{beanName}/{beanId}/favorite', function () use ($app, $ModuleHandler) {
    $app->get('', function ($req, $res, $args) use ($app, $ModuleHandler) {
        $actionData = $ModuleHandler->get_favorite($args['beanName'], $args['beanId']);
        echo json_encode($actionData);
    });
    $app->post('', function ($req, $res, $args) use ($app, $ModuleHandler) {
        $actionData = $ModuleHandler->set_favorite($args['beanName']);
    });
    $app->delete('', function ($req, $res, $args) use ($app, $ModuleHandler) {
        $actionData = $ModuleHandler->delete_favorite($args['beanName']);
    });
});