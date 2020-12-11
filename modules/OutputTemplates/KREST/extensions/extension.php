<?php
use SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->registerExtension('outputtemplates', '1.0');

$RESTManager->app->get('/module/OutputTemplates/formodule/{module}', [new OutputTemplatesController(), 'getModuleTemplates']);

$RESTManager->app->group('/OutputTemplates', function() {
    $this->post('/previewhtml', [new OutputTemplatesController(), 'previewhtml']);
    $this->post('/previewpdf', [new OutputTemplatesController(), 'previewpdf']);
    $this->group('/{id}', function() {
        $this->get('/compile/{bean_id}', [new OutputTemplatesController(), 'compile']);
        $this->get('/convert/{bean_id}/to/{format}', [new OutputTemplatesController(), 'convertToFormat']);
        $this->get('/convert/{bean_id}/to/{format}/base64', [new OutputTemplatesController(), 'convertToBase64']);
    });
});
