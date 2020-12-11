<?php
use SpiceCRM\modules\Users\KREST\controllers\UsersPreferencesKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/user/{userId}/preferences/{category}', function () {
    $this->get('', [new UsersPreferencesKRESTController(), 'getPreferences']);
    // route should get deleted soon
    $this->get('/{names}', [new UsersPreferencesKRESTController(), 'getUserPreferences']);
    $this->post('', [new UsersPreferencesKRESTController(), 'set_user_preferences']);
});
