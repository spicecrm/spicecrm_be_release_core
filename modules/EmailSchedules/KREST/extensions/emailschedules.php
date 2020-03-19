<?php
use SpiceCRM\modules\EmailSchedules\KREST\controllers\EmailSchedulesKRESTController;

$KRESTManager->registerExtension('', '1.0');
$app->post('/modules/EmailSchedules/saveSchedule', [new SpiceCRM\modules\EmailSchedules\KREST\controllers\EmailSchedulesKRESTController(), 'saveSchedule']);
$app->post('/modules/EmailSchedules/saveScheduleFromRelated', [new SpiceCRM\modules\EmailSchedules\KREST\controllers\EmailSchedulesKRESTController(), 'saveScheduleFromRelated']);
$app->get('/module/EmailSchedules/checkRelated/{module}/{id}', [new SpiceCRM\modules\EmailSchedules\KREST\controllers\EmailSchedulesKRESTController(), 'checkRelated']);
$app->get('/module/Users/{id}/myOpenSchedules', [new SpiceCRM\modules\EmailSchedules\KREST\controllers\EmailSchedulesKRESTController(), 'getOwnOpenSchedules']);
