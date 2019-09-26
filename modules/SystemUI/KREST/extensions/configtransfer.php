<?php

$uiRestHandler = new SpiceCRM\modules\SystemUI\SystemUIRESTHandler();

$app->group('/configtransfer', function () use ($app, $uiRestHandler) {
    $this->get( '/tablenames', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer::getSelectableTablenames' );
    $this->post( '/data/export', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer::exportFromTables' );
    $this->post( '/data/import', 'SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIConfigTransfer::importToTables' );
});
