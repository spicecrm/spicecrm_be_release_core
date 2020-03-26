<?php
// require_once 'modules/SystemLanguages/SystemLanguagesRESTHandler.php';
$handler = new SpiceCRM\modules\SystemLanguages\SystemLanguagesRESTHandler();

$app->group('/syslanguages', function () use ($app, $handler)
{
    $app->group('/labels', function() use ($app, $handler)
    {
        $app->post('', function($req, $res, $args) use($app, $handler) {
            $result = $handler->saveLabels($req->getParsedBody());
            echo json_encode($result);
        });
        $app->delete('/{id}/[{environment}]', function($req, $res, $args) use($app, $handler) {
            // delete a specific label...
            $result = $handler->deleteLabel($args['id'], $args['environment']);
            echo json_encode($result);
        });
        $app->get('/search/{search_term}', function($req, $res, $args) use($app, $handler){
            $result = $handler->searchLabels($args['search_term']);
            echo json_encode($result);
        });
    });

    $app->group('/load', function() use ($app, $handler)
    {
        $app->get('/{language}', function($req, $res, $args) use($app, $handler){
            $params = $_GET;
            $params['language'] = $args['language'];
            $result = $handler->loadSysLanguages($params);
            echo json_encode($result);

        });
    });

    $app->post('/setdefault/{language}', function($req, $res, $args){
        global $db, $current_user;

        if(!$current_user->is_admin){
            return $res->write(json_encode(['success' => false]));
        }

        $db->query("UPDATE syslangs SET is_default = 0 WHERE is_default = 1");
        $db->query("UPDATE syslangs SET is_default = 1 WHERE language_code = '{$args['language']}'");

        return $res->write(json_encode(['success' => true]));

    });

    $app->post( '/filesToDB', function( $req, $res, $args ) use($app, $handler) {

        if ( !$GLOBALS['current_user']->is_dev )
            throw (new \SpiceCRM\KREST\ForbiddenException('No development privileges.'))->setErrorCode('notDeveloper');

        if ( $req->getParsedBodyParam('confirmed' ) !== true )
            throw (new \SpiceCRM\KREST\BadRequestException('Operation not confirmed.'))->setErrorCode('notConfirmed');

        $result = $handler->transferFromFilesToDB( $args );
        if ( $result === false ) return $res->withJson(['success' => false]);
        else return $res->withJson( $result );

    });

});

$app->get('/syslanguage/{language}/{scope}/labels/untranslated', function($req, $res, $args) use($app, $handler){
    global $current_user;
    if (!$current_user->is_admin)
        throw (new \SpiceCRM\KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

    echo json_encode($handler->getUntranslatedLabels($args['language'], $args['scope']));

});
