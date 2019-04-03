<?php
$app->group('/module/History', function (){
    $this->post('/fts/{parentmodule}/{parentid}', 'SpiceCRM\modules\History\KREST\controllers\HistoryKRESTController::loadFTSHistory');
    $this->get('/{parentmodule}/{parentid}', 'SpiceCRM\modules\History\KREST\controllers\HistoryKRESTController::loadHistory');
});