<?php
use SpiceCRM\modules\Administration\KREST\CleanUpHandler;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$handler = new CleanUpHandler();

$RESTManager->app->group('/cleanup', function() use($handler) {
    $this->group('/configs', function() use($handler) {
        $this->group('/check', function() use($handler) {
            $this->get('/incomplete[/{scope}]', function ($req, $res, $args) use($handler) {
                if($args['scope']) {
                    $handler->scope = $args['scope'];
                }
                return $res->withJson($handler->getIncompleteRecords());
            });

            $this->get('/unused[/{scope}]', function ($req, $res, $args) use($handler) {
                if($args['scope']) {
                    $handler->scope = $args['scope'];
                }
                return $res->withJson($handler->getUnusedRecords());
            });

            $this->get('/duplications[/{scope}]', function ($req, $res, $args) use($handler) {
                if($args['scope']) {
                    $handler->scope = $args['scope'];
                }
            });
        });
    });
});
