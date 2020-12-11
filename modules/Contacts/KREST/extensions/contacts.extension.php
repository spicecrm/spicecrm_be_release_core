<?php
use SpiceCRM\modules\Contacts\KREST\controllers\ContactsController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->registerExtension('exchange', '1.0');

$RESTManager->app->put('/module/Contacts/{id}/exchangeSync', [new ContactsController(), 'ewsSync']);
$RESTManager->app->delete('/module/Contacts/{id}/exchangeSync', [new ContactsController(), 'ewsDelete']);
