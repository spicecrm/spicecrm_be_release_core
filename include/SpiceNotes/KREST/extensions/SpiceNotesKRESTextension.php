<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceNotes\SpiceNotes;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/{beanName}/{beanId}/note', function () {
    $this->get('', function ($req, $res, $args) {
        echo SpiceNotes::getQuickNotesForBean($args['beanName'], $args['beanId']);
    });
    $this->post('', function ($req, $res, $args) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        $data = array_merge($postBody, $postParams);
        echo SpiceNotes::saveQuickNote($args['beanName'], $args['beanId'], $data);

    });
    $this->post('/{noteId}', function ($req, $res, $args) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        $data = array_merge($postBody, $postParams);
        echo SpiceNotes::editQuickNote($args['beanName'], $args['beanId'], $data);
    });
    $this->delete('/{noteId}', function ($req, $res, $args) {
        echo SpiceNotes::deleteQuickNote($args['noteId']);
    });
});
