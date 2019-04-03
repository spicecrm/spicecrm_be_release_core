<?php
$app->group('/SpiceReminders', function () use ($app, $uiRestHandler) {
    $app->get('', 'SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController::getReminders');
    $app->post('/{module}/{id}/{date}', 'SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController::addReminder');
    $app->delete('/{module}/{id}', 'SpiceCRM\includes\SpiceReminders\KREST\controllers\SpiceRemindersKRESTController::deleteReminder');
});