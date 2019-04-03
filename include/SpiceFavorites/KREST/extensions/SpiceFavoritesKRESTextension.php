<?php
$app->group('/SpiceFavorites', function () use ($app, $uiRestHandler) {
    $app->get('', 'SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController::getFavorites');
    $app->post('/{module}/{id}', 'SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController::addFavorite');
    $app->delete('/{module}/{id}', 'SpiceCRM\includes\SpiceFavorites\KREST\controllers\SpiceFavoritesKRESTController::deleteFavorite');
});