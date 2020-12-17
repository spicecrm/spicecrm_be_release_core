<?php

$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('leads', '2.0');
$RESTManager->app->post('/module/Leads/createFromForm', [new \SpiceCRM\modules\Leads\KREST\controllers\LeadsKRESTController(), 'createFromForm']);
