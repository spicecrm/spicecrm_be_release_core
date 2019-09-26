<?php
$app->group('/user/{userId}/preferences/{category}', function () use ( $app ) {
    $this->get('', [new \SpiceCRM\modules\Users\KREST\controllers\UsersPreferencesKRESTController(), 'getPreferences']);
    // route should get deleted soon
    $this->get('/{names}', [new \SpiceCRM\modules\Users\KREST\controllers\UsersPreferencesKRESTController(), 'getUserPreferences']);
    $this->post('', [new \SpiceCRM\modules\Users\KREST\controllers\UsersPreferencesKRESTController(), 'set_user_preferences']);
});
