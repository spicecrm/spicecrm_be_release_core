<?php
use SpiceCRM\modules\ProspectLists\KREST\controllers\ProspetListsKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->post('/modules/ProspectLists/createfromlist/{listid}', 'SpiceCRM\modules\ProspectLists\KREST\controllers\ProspetListsKRESTController::createFromListId');
$RESTManager->app->post('/modules/ProspectLists/exportFromList', 'SpiceCRM\modules\ProspectLists\KREST\controllers\ProspetListsKRESTController::exportFromList');

