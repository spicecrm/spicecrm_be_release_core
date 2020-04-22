<?php

$KRESTManager->registerExtension('outputtemplates', '1.0');

$app->get('/module/OutputTemplates/formodule/{module}', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'getModuleTemplates']);

$app->group('/OutputTemplates', function() {
    $this->post('/previewhtml', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'previewhtml']);
    $this->post('/previewpdf', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'previewpdf']);
    $this->group('/{id}', function() {
        $this->get('/compile/{bean_id}', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'compile']);
        $this->get('/convert/{bean_id}/to/{format}', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'convertToFormat']);
        $this->get('/convert/{bean_id}/to/{format}/base64', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'convertToBase64']);
    });
});
