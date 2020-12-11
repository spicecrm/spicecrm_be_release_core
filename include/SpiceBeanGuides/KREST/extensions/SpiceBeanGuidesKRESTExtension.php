<?php
use SpiceCRM\includes\SpiceBeanGuides\KREST\controllers\SpiceBeanGuidesKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/spicebeanguide/{module}', function () {
    $this->get('', 'SpiceCRM\includes\SpiceBeanGuides\KREST\controllers\SpiceBeanGuidesKRESTController::getStages');
    $this->get('/{beanid}', 'SpiceCRM\includes\SpiceBeanGuides\KREST\controllers\SpiceBeanGuidesKRESTController::getBeanStages');
});
