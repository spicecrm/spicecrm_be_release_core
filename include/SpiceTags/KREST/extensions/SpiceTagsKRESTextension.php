<?php
$app->group('/SpiceTags', function () use ($app, $uiRestHandler) {
    $app->get('/{query}', 'SpiceCRM\includes\SpiceTags\KREST\controllers\SpiceTagsKRESTController::searchTags');
    $app->post('', 'SpiceCRM\includes\SpiceTags\KREST\controllers\SpiceTagsKRESTController::searchPostTags');
});