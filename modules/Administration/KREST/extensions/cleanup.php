<?php
require_once('modules/Administration/KREST/cleanup_handler.php');
$handler = new CleanUpHandler();

$app->group('/cleanup', function() use($handler)
{
    $this->group('/configs', function() use($handler)
    {
        $this->group('/check', function() use($handler)
        {
            $this->get('/incomplete[/{scope}]', function ($req, $res, $args) use($handler) {
                if($args['scope'])
                    $handler->scope = $args['scope'];
                return $res->withJson($handler->getIncompleteRecords());
            });

            $this->get('/unused[/{scope}]', function ($req, $res, $args) use($handler) {
                if($args['scope'])
                    $handler->scope = $args['scope'];
                return $res->withJson($handler->getUnusedRecords());
            });

            $this->get('/duplications[/{scope}]', function ($req, $res, $args) use($handler){
                if($args['scope'])
                    $handler->scope = $args['scope'];

            });
        });
    });
});