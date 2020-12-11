<?php
use SpiceCRM\modules\History\KREST\controllers\HistoryKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/History', function () {
    $this->post('/fts/{parentmodule}/{parentid}', [new HistoryKRESTController(), 'loadFTSHistory']);
    $this->get('/{parentmodule}/{parentid}', new HistoryKRESTController(), 'loadHistory');
});
