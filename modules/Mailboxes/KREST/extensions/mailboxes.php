<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\MailboxesController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$controller = new MailboxesController();
$RESTManager->registerExtension('mailboxes', '1.0');

$RESTManager->app->get('/modules/Mailboxes/{id}/fetchemails', [new MailboxesController(), 'fetchEmails']);
$RESTManager->app->get('/modules/Mailboxes/dashlet', [new MailboxesController(), 'getMailboxesForDashlet']);
$RESTManager->app->get('/modules/Mailboxes/dashlet/{type}', [new MailboxesController(), 'getMailboxesForDashlet']);
