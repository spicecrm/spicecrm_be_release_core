<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/SpiceReminders', function () {
    $this->get('', 'SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController::getReminders');
    $this->post('/{module}/{id}/{date}', 'SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController::addReminder');
    $this->delete('/{module}/{id}', 'SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController::deleteReminder');
});
