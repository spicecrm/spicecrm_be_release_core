<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/SpiceFavorites', function () {
    $this->get('', 'SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController::getFavorites');
    $this->post('/{module}/{id}', 'SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController::addFavorite');
    $this->delete('/{module}/{id}', 'SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController::deleteFavorite');
});
