<?php

use SpiceCRM\modules\Mailboxes\KREST\controllers\ImapController;

$KRESTManager->registerExtension('imap', '1.0');

$app->group('/mailboxes/imap', function () {
    $this->post('/getmailboxfolders', [new ImapController(), 'getMailboxFolders']);
});
