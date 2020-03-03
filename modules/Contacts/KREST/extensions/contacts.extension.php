<?php
use SpiceCRM\modules\Contacts\KREST\controllers\ContactsController;

$KRESTManager->registerExtension('exchange', '1.0');

$app->put('/module/Contacts/{id}/exchangeSync', [new ContactsController(), 'ewsSync']);
$app->delete('/module/Contacts/{id}/exchangeSync', [new ContactsController(), 'ewsDelete']);
