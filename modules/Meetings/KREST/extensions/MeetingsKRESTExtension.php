<?php

$app->post('/modules/Meetings/{id}/setstatus/{userid}/{status}', 'SpiceCRM\modules\Meetings\KREST\controllers\MeetingsKRESTController::setStatus');
