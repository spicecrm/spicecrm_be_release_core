<?php
use SpiceCRM\includes\RESTManager;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/{beanName}/{beanId}/reminder', function ()  {
    $this->get('', function ($req, $res, $args) {
        require_once('modules/SpiceThemeController/SpiceThemeController.php');
        $SpiceThemeController = new SpiceThemeController();
        echo $SpiceThemeController->getReminder();
    });
    $this->post('', function ($req, $res, $args) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        $data = array_merge($postBody, $postParams);
        require_once('modules/SpiceThemeController/SpiceThemeController.php');
        $SpiceThemeController = new SpiceThemeController();
        echo $SpiceThemeController->setReminder($args['beanName'], $args['beanId'], $data);
    });
    $this->delete('', function ($req, $res, $args) {
        require_once('modules/SpiceThemeController/SpiceThemeController.php');
        $SpiceThemeController = new SpiceThemeController();
        echo $SpiceThemeController->removeReminder($args['beanName'], $args['beanId']);
    });
});
