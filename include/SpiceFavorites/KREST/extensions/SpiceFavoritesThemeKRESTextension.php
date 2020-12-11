<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\KREST\handlers\ModuleHandler;

$RESTManager = RESTManager::getInstance();
$ModuleHandler = new ModuleHandler($RESTManager->app);

$RESTManager->app->group('/module/{beanName}/{beanId}/favorite', function () use ($ModuleHandler) {
    $this->get('', function ($req, $res, $args) use ($ModuleHandler) {
        $actionData = $ModuleHandler->get_favorite($args['beanName'], $args['beanId']);
        echo json_encode($actionData);
    });
    $this->post('', function ($req, $res, $args) use ($ModuleHandler) {
        $actionData = $ModuleHandler->set_favorite($args['beanName']);
    });
    $this->delete('', function ($req, $res, $args) use ($ModuleHandler) {
        $actionData = $ModuleHandler->delete_favorite($args['beanName']);
    });
});
