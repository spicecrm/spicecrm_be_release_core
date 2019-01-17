<?php
$handler = new \SpiceCRM\modules\Mailboxes\MailboxesRESTHandler();

$KRESTManager->registerExtension('mailboxes', '1.0');

$app->get('/modules/Mailboxes/{id}/fetchemails', function($req, $res, $args) use ($app, $handler) {
    $result = $handler->fetchEmails($args['id']);
    echo json_encode($result);
});

$app->get('/modules/Mailboxes/dashlet', function($req, $res, $args) use ($app, $handler) {
    $mailboxes = $handler->getMailboxesForDashlet();
    echo json_encode($mailboxes);
});

$app->group('/mailboxes', function () use ($app, $handler) {
    $app->get('/test', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->testConnection($req->getQueryParams());
        echo json_encode($result);
    });

    $app->get('/getmailboxprocessors', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->getMailboxProcessors();
        echo json_encode($result);
    });

    $app->post('/sendmail', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->sendMail($req->getParsedBody(), $req->getUploadedFiles());
        echo json_encode($result);
    });

    $app->get('/getmailboxes', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->getMailboxes($_GET);
        echo json_encode($result);
    });

    $app->get('/setdefaultmailbox', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->setDefaultMailbox($req->getQueryParams());
        echo json_decode($result);
    });

    $app->group('/imap', function () use ($app, $handler) {
        $app->get('/getmailboxfolders', function($req, $res, $args) use ($app, $handler) {
            $result = $handler->getMailboxFolders($req->getQueryParams());
            echo json_encode($result);
        });

    });

    $app->group('/email', function () use ($app, $handler) {
        $app->get('/html', function ($req, $res, $args) use ($app, $handler) {
            $guid  = $req->getQueryParams()['email_id'];
            $email = \BeanFactory::getBean('Emails', $guid);
            echo html_entity_decode($email->body);
        });
    });

    $app->group('/sendgrid', function () use ($app, $handler) {
        $app->get('/eventhandler', function ($req, $res, $args) use ($app, $handler) {
            $handler->handleSendgridEvents($req->getQueryParams());
        });
    });
});
