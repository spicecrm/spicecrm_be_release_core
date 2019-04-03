<?php

/**
 * returns the recent items. Accepts as call paramaters the module and the limit of records to be retirvbed. If no module is sent in teh params this is a global request
 */
$app->get('/modules/Trackers/recent', 'SpiceCRM\modules\Trackers\KREST\controllers\TrackersKRESTController::getRecent');