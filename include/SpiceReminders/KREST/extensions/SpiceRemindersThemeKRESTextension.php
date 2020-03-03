<?php
$app->group('/module/{beanName}/{beanId}/reminder', function () use ($app) {
    $app->get('', function ($req, $res, $args) use ($app) {
        require_once('modules/SpiceThemeController/SpiceThemeController.php');
        $SpiceThemeController = new SpiceThemeController();
        echo $SpiceThemeController->getReminder();
    });
    $app->post('', function ($req, $res, $args) use ($app) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        $data = array_merge($postBody, $postParams);
        require_once('modules/SpiceThemeController/SpiceThemeController.php');
        $SpiceThemeController = new SpiceThemeController();
        echo $SpiceThemeController->setReminder($args['beanName'], $args['beanId'], $data);
    });
    $app->delete('', function ($req, $res, $args) use ($app) {
        require_once('modules/SpiceThemeController/SpiceThemeController.php');
        $SpiceThemeController = new SpiceThemeController();
        echo $SpiceThemeController->removeReminder($args['beanName'], $args['beanId']);
    });
});
