<?php
$app->get('/module/OutputTemplates/formodule/{module}', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'getModuleTemplates']);

$app->group('/OutputTemplates', function() {

    $this->get('/{id}/compile/{bean_id}', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'compile']);


    $this->get('/{id}/convert/{bean_id}/to/{format}', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'convertToFormat']);

    $this->get('/{id}/convert/{bean_id}/to/{format}/base64', [new \SpiceCRM\modules\OutputTemplates\KREST\controllers\OutputTemplatesController(), 'convertToBase64']);
});