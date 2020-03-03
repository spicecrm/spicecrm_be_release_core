<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\MailboxesController;
$controller = new MailboxesController();
$KRESTManager->registerExtension('mailboxmanager', '1.0');

$app->group('/mailboxes', function () {
    $this->post('/test', [new MailboxesController(), 'testConnection']);
    $this->get('/transports', [new MailboxesController(), 'getMailboxTransports']);
    $this->get('/getmailboxprocessors', [new MailboxesController(), 'getMailboxProcessors']);
    $this->post('/sendmail', [new MailboxesController(), 'sendMail']);
    $this->get('/getmailboxes', [new MailboxesController(), 'getMailboxes']);
    $this->get('/setdefaultmailbox', [new MailboxesController(), 'setDefaultMailbox']);
});
