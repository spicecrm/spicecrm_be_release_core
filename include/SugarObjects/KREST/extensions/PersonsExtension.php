<?php

$app->group('/{module}', function() {
    $this->get('/convert/{id}/to/VCard', [new SpiceCRM\includes\SugarObjects\KREST\controllers\PersonsController(), 'convertToVCard']);
});
