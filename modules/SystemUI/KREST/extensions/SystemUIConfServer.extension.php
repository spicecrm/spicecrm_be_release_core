<?php
global $sugar_config;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

// check if system shoudl act as configrepository
if($sugar_config['configrepository']['enabled']) {

    // check if system shoudl act as public configrepository - so no authentication is required
    if($sugar_config['configrepository']['public']){
        $RESTManager->excludeFromAuthentication('/config');
        $RESTManager->excludeFromAuthentication('/config/*');
    }

    $RESTManager->app->group('/config', function () {
        $this->get('', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController::getAvailable');
        $this->get('/repositoryitems', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController::getRepositoryItems');
        $this->get('/repositorymodules', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController::getRepositoryModules');
        $this->get('/{packages}/{version}', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController::getConfig');

    });

    $RESTManager->app->get('/config/language/{language}/{package}/{version}','SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController::getLanguageLabels');
    $RESTManager->app->get('/config/language/{language}','SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfServerController::getLanguageLabels');
}
