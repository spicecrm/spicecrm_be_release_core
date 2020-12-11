<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\MailboxesController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$controller = new MailboxesController();
$RESTManager->registerExtension('mailboxmanager', '1.0');

$RESTManager->app->group('/mailboxes', function () {
    $this->post('/test', [new MailboxesController(), 'testConnection']);
    $this->get('/transports', [new MailboxesController(), 'getMailboxTransports']);
    $this->get('/getmailboxprocessors', [new MailboxesController(), 'getMailboxProcessors']);
    $this->post('/sendmail', [new MailboxesController(), 'sendMail']);
    $this->get('/getmailboxes', [new MailboxesController(), 'getMailboxes']);
    $this->get('/setdefaultmailbox', [new MailboxesController(), 'setDefaultMailbox']);
});
