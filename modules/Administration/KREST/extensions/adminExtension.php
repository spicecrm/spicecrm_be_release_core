<?php
$app->group('/admin', function () use ($app) {
    $app->get('/systemstats', [new \SpiceCRM\modules\Administration\KREST\controllers\adminController(), 'systemstats']);
});