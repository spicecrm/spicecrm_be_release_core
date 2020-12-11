<?php
use SpiceCRM\modules\EmailSchedules\KREST\controllers\EmailSchedulesKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->registerExtension('emailschedules', '1.0');
$RESTManager->app->post('/modules/EmailSchedules/saveSchedule', [new EmailSchedulesKRESTController(), 'saveSchedule']);
$RESTManager->app->post('/modules/EmailSchedules/saveScheduleFromRelated', [new EmailSchedulesKRESTController(), 'saveScheduleFromRelated']);
$RESTManager->app->get('/module/EmailSchedules/checkRelated/{module}/{id}', [new EmailSchedulesKRESTController(), 'checkRelated']);
$RESTManager->app->get('/module/Users/{id}/myOpenSchedules', [new EmailSchedulesKRESTController(), 'getOwnOpenSchedules']);
