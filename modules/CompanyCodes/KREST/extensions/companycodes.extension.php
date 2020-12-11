<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 16.06.2019
 * Time: 21:02
 */
use SpiceCRM\modules\CompanyCodes\KREST\controllers\CompanyCodesKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

//$KRESTManager->registerExtension('companycodes', '1.0');

$RESTManager->app->group('/CompanyCodes', function () {
    $this->get('', 'SpiceCRM\modules\CompanyCodes\KREST\controllers\CompanyCodesKRESTController::getCompanyCodes');
});
