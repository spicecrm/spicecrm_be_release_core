<?php
use SpiceCRM\modules\Administration\KREST\controllers\adminController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/admin', function () {
    $this->get('/systemstats', [new adminController(), 'systemstats']);
    $this->get('/generalsettings', [new adminController(), 'getGeneralSettings']);
    $this->post('/writesettings', [new adminController(), 'writeGeneralSettings']);
});
