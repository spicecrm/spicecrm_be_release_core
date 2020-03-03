<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\MailboxesController;
$controller = new MailboxesController();
$KRESTManager->registerExtension('mailboxes', '1.0');

$app->get('/modules/Mailboxes/{id}/fetchemails', [new MailboxesController(), 'fetchEmails']);
$app->get('/modules/Mailboxes/dashlet', [new MailboxesController(), 'getMailboxesForDashlet']);
$app->get('/modules/Mailboxes/dashlet/{type}', [new MailboxesController(), 'getMailboxesForDashlet']);
