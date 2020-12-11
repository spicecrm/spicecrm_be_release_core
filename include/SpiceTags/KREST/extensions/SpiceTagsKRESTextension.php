<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceTags\KREST\controllers\SpiceTagsKRESTController;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/SpiceTags', function () {
    $this->get('/{query}', 'SpiceCRM\includes\SpiceTags\KREST\controllers\SpiceTagsKRESTController::searchTags');
    $this->post('', 'SpiceCRM\includes\SpiceTags\KREST\controllers\SpiceTagsKRESTController::searchPostTags');
});
