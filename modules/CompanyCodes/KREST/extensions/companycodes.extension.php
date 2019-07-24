<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 16.06.2019
 * Time: 21:02
 */

//$KRESTManager->registerExtension('companycodes', '1.0');

$app->group('/CompanyCodes', function () use ($app, $uiRestHandler) {
    $app->get('', 'SpiceCRM\modules\CompanyCodes\KREST\controllers\CompanyCodesKRESTController::getCompanyCodes');
});