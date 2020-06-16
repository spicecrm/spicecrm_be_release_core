<?php
$app->group('/admin', function () use ($app) {
    $app->get('/systemstats', [new \SpiceCRM\modules\Administration\KREST\controllers\adminController(), 'systemstats']);
    $app->get('/generalsettings', [new \SpiceCRM\modules\Administration\KREST\controllers\adminController(), 'getGeneralSettings']);
    $app->post('/writesettings', [new \SpiceCRM\modules\Administration\KREST\controllers\adminController(), 'writeGeneralSettings']);
});
