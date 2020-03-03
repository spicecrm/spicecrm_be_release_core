<?php

$app->post('/modules/Calls/{id}/setstatus/{userid}/{status}', 'SpiceCRM\modules\Calls\KREST\controllers\CallsKRESTController::setStatus');
