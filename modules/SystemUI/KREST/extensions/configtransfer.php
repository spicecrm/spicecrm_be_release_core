<?php
use SpiceCRM\modules\SystemUI\SystemUIRESTHandler;
use SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$uiRestHandler = new SystemUIRESTHandler();

$RESTManager->app->group('/configtransfer', function () use ($uiRestHandler) {
    $this->get( '/tablenames', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer::getSelectableTablenames' );
    $this->post( '/data/export', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer::exportFromTables' );
    $this->post( '/data/import', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer::importToTables' );
});
