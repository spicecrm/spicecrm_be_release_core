<?php
use SpiceCRM\modules\Emails\KREST\controllers\EmailsKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/Emails', function () {

    $this->group('/groupware', function() {
        $this->post('/saveGSuiteEmailWithBeans', [new EmailsKRESTController(), 'saveGSuiteEmailWithBeans']);
        $this->post('/saveOutlookEmailWithBeans', [new EmailsKRESTController(), 'saveOutlookEmailWithBeans']);
        $this->post('/getemail', [new EmailsKRESTController(), 'getEmail']);
        $this->post('/saveOutlookAttachments', [new EmailsKRESTController(), 'saveOutlookAttachments']);
        $this->post('/saveGSuiteAttachments', [new EmailsKRESTController(), 'saveGSuiteAttachments']);
        $this->post('/search', [new EmailsKRESTController(), 'search']);
    });

    $this->group('/{id}', function () {
        $this->post('/setstatus/{status}', [new EmailsKRESTController(), 'setStatus']);
        $this->post('/setopenness/{openness}', [new EmailsKRESTController(), 'setOpenness']);
        $this->get('/process', [new EmailsKRESTController(), 'process']);
    });

    $this->group('/msg', function() {
        $this->post('', [new EmailsKRESTController(), 'createEmailFromMSGFile']);
        $this->group('/{attachmentId}', function () {
            //  $this->get('/parse', [new EmailsKRESTController(), 'parseMsgAttachment']);
            $this->get('/preview', [new EmailsKRESTController(), 'previewMsgFromAttachment']);
        });
    });
});
