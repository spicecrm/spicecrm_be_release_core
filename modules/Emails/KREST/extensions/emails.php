<?php
use SpiceCRM\modules\Emails\KREST\controllers\EmailsKRESTController;

$app->group('/module/Emails', function () use ($app) {

    $app->group('/groupware', function() {
        $this->post('/saveemailwithbeans', [new EmailsKRESTController(), 'saveEmailWithBeans']);
        $this->post('/getemail', [new EmailsKRESTController(), 'getEmail']);
        $this->post('/saveaddinattachments', [new EmailsKRESTController(), 'saveAttachments']);
        $this->post('/search', [new EmailsKRESTController(), 'search']);
    });

    $app->group('/{id}', function () {
        $this->post('/setstatus/{status}', [new EmailsKRESTController(), 'setStatus']);
        $this->post('/setopenness/{openness}', [new EmailsKRESTController(), 'setOpenness']);
        $this->get('/process', [new EmailsKRESTController(), 'process']);
    });
});
