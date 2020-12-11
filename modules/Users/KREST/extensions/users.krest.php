<?php
use SpiceCRM\modules\Users\KREST\controllers\UsersKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/Users/{id}', function () {
    $this->post('', [new UsersKRESTController(), 'saveUser'] );
    $this->delete( '', [new UsersKRESTController(), 'setUserInactive'] );

    // CR1000453
    $this->group('/deactivate', function () {
        $this->get('', [new UsersKRESTController(), 'getDeactivateUserStats'] );
        $this->post('', [new UsersKRESTController(), 'deactivateUser'] );
    });
    $this->group('/activate', function () {
        $this->post('', [new UsersKRESTController(), 'activateUser'] );
    });
});
