<?php

/*
 * deprectated fts routes
 * this has been moved to SpiceFTSManager/KREST/extensions/search.php and a new route search with post params
 */
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler;
use SpiceCRM\includes\ErrorHandlers\ServiceUnavailableException;

$RESTManager = RESTManager::getInstance();
$RESTManager->excludeFromAuthentication('/fts/check');
$ftsManager = new SpiceFTSHandler();

$RESTManager->app->group('/fts', function () use ($ftsManager) {
//@deprecated
//    $app->post('/search/{module}', function ($req, $res, $args) use ($app, $ftsManager) {
//        $getParams = $_GET;
//        $postBody = $req->getParsedBody();
//        echo json_encode($ftsManager->getSearchResults($args['module'], $postBody['searchterm'], $postBody['page'], $postBody['aggregates']));
//    });
    $this->get('/globalsearch', function () use ($ftsManager) {
        $getParams = $_GET;
        echo json_encode($ftsManager->getGlobalSearchResults('', '', $getParams));
    });
    $this->post('/globalsearch', function () use ($ftsManager) {
        $getParams = $_GET;
        echo json_encode($ftsManager->getGlobalSearchResults('', '', $getParams));
    });
    $this->get('/globalsearch/{module}', function ($req, $res, $args) use ($ftsManager) {
        $getParams = $_GET;
        echo json_encode($ftsManager->getGlobalSearchResults($args['module'], '', $getParams));
    });
    $this->post('/globalsearch/{module}', function ($req, $res, $args) use ($ftsManager) {
        $postBody = $req->getParsedBody();
        $result = $ftsManager->getGlobalSearchResults($args['module'], '', $_GET, $postBody['aggregates'], $postBody['sort']);
        echo json_encode($result);
    });
    $this->get('/globalsearch/{module}/{searchterm}', function ($req, $res, $args) use ($ftsManager) {
        $getParams = $_GET;
        echo json_encode($ftsManager->getGlobalSearchResults( $args['module'], urlencode( $args['searchterm'] ), $getParams));
    });
    $this->post('/globalsearch/{module}/{searchterm}', function ($req, $res, $args) use ($ftsManager) {
        $getParams = $_GET;
        $postBody = $req->getParsedBody();
        echo json_encode($ftsManager->getGlobalSearchResults( $args['module'], urlencode( $args['searchterm'] ), $getParams, $postBody['aggregates'], $postBody['sort'] ));
    });
    $this->get('/searchmodules', function () use ($ftsManager) {
        echo json_encode($ftsManager->getGlobalSearchModules());
    });
    $this->get('/searchterm/{searchterm}', function ($req, $res, $args) use ($ftsManager) {
        $getParams = $_GET;
        echo json_encode($ftsManager->searchTerm( urlencode( $args['searchterm'] ), [], $getParams['size'] ?: 10, $getParams['from'] ?: 0 ));
    });
    $this->get('/check', function ($req, $res, $args) use ($ftsManager) {
        if(!$ftsManager->check()){
            throw (new ServiceUnavailableException('FTS Service unavailable'));
        }
        return $res->withJson(['ftsstatus' => true]);
    });
    $this->get('/status', function () use ($ftsManager) {
        echo json_encode(['version' => $ftsManager->getStatus(), 'stats' => $ftsManager->getStats(), 'settings' => $ftsManager->getSettings()]);
    });
    $this->get('/stats', function () use ($ftsManager) {
        echo json_encode($ftsManager->getStats());
    });
    $this->put('/unblock', function () use ($ftsManager) {
        echo json_encode($ftsManager->unblock());
    });
    $this->get('/fields/{module}', function ($req, $res, $args) use ($ftsManager) {
        return $res->withJson($ftsManager->getFTSModuleFields($args['module']));
    });
});
