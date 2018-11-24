<?php
require_once('modules/ProjectWBSs/ProjectWBS.php');

$KRESTManager->registerExtension('projectmanagement', '1.0');

$app->group('/projectwbs', function () use ($app)
{
    $app->group('/my', function () use ($app)
    {
        $app->get('/wbss', function () use ($app) {
            $wbs = new ProjectWBS();
            echo json_encode($wbs->getMyWBSs());
        });
    });

    $app->get('/{id}', function($req, $res, $args) use ($app) {
        $wbs = new ProjectWBS();
        $list = $wbs->getList($args['id']);
        echo json_encode($list);
    });
    $app->post('', function($req, $res, $args) use ($app) {
        $wbs = new ProjectWBS();
        $postBody = $req->getParsedBody();
        $postParams = $_GET;
        $params = array_merge($postBody, $postParams);
        $res = $wbs->saveWBS($params);
        echo json_encode($res);
    });
    $app->delete('/{id}', function($req, $res, $args) use ($app) {
        $wbs = new ProjectWBS();
        $list = $wbs->delete_recursive($args['id']);
        echo json_encode(array('status' => 'DELETED ' . $args['id']));
    });
});
