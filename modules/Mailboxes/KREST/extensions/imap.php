<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\MailboxesController;

$controller = new MailboxesController();

$KRESTManager->registerExtension('mailboxes', '1.0');

$app->get('/modules/Mailboxes/{id}/fetchemails', [new MailboxesController(), 'fetchEmails']);

$app->get('/modules/Mailboxes/dashlet', [new MailboxesController(), 'getMailboxesForDashlet']);
$app->get('/modules/Mailboxes/dashlet/{type}', [new MailboxesController(), 'getMailboxesForDashlet']);

$app->group('/mailboxes', function () {
    $this->get('/test', [new MailboxesController(), 'testConnection']);

    $this->get('/getmailboxprocessors', [new MailboxesController(), 'getMailboxProcessors']);

    $this->post('/sendmail', [new MailboxesController(), 'sendMail']);

    $this->get('/getmailboxes', [new MailboxesController(), 'getMailboxes']);

    $this->get('/setdefaultmailbox', [new MailboxesController(), 'setDefaultMailbox']);

    $this->group('/imap', function () {
        $this->get('/getmailboxfolders', [new MailboxesController(), 'getMailboxFolders']);
    });

//    $this->group('/email', function () {
//        $app->get('/html', function ($req, $res, $args) use ($app, $handler) {
//            $guid  = $req->getQueryParams()['email_id'];
//            $email = \BeanFactory::getBean('Emails', $guid);
//            echo html_entity_decode($email->body);
//        });
//    });

    $this->group('/sendgrid', function () {
        $this->get('/eventhandler', [new MailboxesController(), 'handleSendgridEvents']);
    });
});
