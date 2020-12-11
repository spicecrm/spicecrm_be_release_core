<?php
use SpiceCRM\modules\Mailboxes\KREST\controllers\ImapController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->registerExtension('imap', '1.0');

$RESTManager->app->group('/mailboxes/imap', function () {
    $this->post('/getmailboxfolders', [new ImapController(), 'getMailboxFolders']);
});
