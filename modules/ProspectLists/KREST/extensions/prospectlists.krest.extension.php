<?php


$app->post('/modules/ProspectLists/createfromlist/{listid}', 'SpiceCRM\modules\ProspectLists\KREST\controllers\ProspetListsKRESTController::createFromListId');
$app->post('/modules/ProspectLists/exportFromList', 'SpiceCRM\modules\ProspectLists\KREST\controllers\ProspetListsKRESTController::exportFromList');

