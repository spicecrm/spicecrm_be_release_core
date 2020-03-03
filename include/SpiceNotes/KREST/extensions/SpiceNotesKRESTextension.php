<?php
$app->group('/module/{beanName}/{beanId}/note', function () use ($app) {
        $app->get('', function ($req, $res, $args) use ($app) {
            require_once('modules/SpiceThemeController/SpiceThemeController.php');
            $SpiceThemeController = new SpiceThemeController();
            echo $SpiceThemeController->getQuickNotes($args['beanName'], $args['beanId']);
        });
        $app->post('', function ($req, $res, $args) use ($app) {
            require_once('modules/SpiceThemeController/SpiceThemeController.php');
            $postBody = $body = $req->getParsedBody();
            $postParams = $_GET;
            $data = array_merge($postBody, $postParams);
            $SpiceThemeController = new SpiceThemeController();
            echo $SpiceThemeController->saveQuickNote($args['beanName'], $args['beanId'], $data);
        });
        $app->post('/{noteId}', function ($req, $res, $args) use ($app) {
            require_once('modules/SpiceThemeController/SpiceThemeController.php');
            $postBody = $body = $req->getParsedBody();
            $postParams = $_GET;
            $data = array_merge($postBody, $postParams);
            $SpiceThemeController = new SpiceThemeController();
            echo $SpiceThemeController->editQuickNote($args['beanName'], $args['beanId'], $args['noteId'], $data);
        });
        $app->delete('/{noteId}', function ($req, $res, $args) use ($app) {
            require_once('modules/SpiceThemeController/SpiceThemeController.php');
            $SpiceThemeController = new SpiceThemeController();
            echo $SpiceThemeController->deleteQuickNote($args['noteId']);
        });
});
