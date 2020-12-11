<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\GmailController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->registerExtension('gmail', '1.0');

$RESTManager->app->post('/mailboxes/gmail/getMailboxLabels', [new GmailController(), 'getMailboxLabels']);
