<?php

use \SpiceCRM\modules\Users\KREST\controllers\UsersKRESTController;

$app->group('/module/Users/{id}', function () use ($app) {
    $app->post('', [new UsersKRESTController(), 'saveUser'] );
    $app->delete( '', [new UsersKRESTController(), 'setUserInactive'] );
});
