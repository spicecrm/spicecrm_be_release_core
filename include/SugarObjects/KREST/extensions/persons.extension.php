<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SugarObjects\KREST\controllers\PersonsController;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/{module}', function() {
    $this->get('/convert/{id}/to/VCard', [new PersonsController(), 'convertToVCard']);
});
