<?php
use ProjectWBS;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->registerExtension('projectmanagement', '1.0');

$RESTManager->app->group('/projectwbs', function () {
    $this->group('/my', function () {
        $this->get('/wbss', function () {
            $wbs = new ProjectWBS();
            echo json_encode($wbs->getMyWBSs());
        });
    });

    $this->get('/{id}', function($req, $res, $args) {
        $wbs = new ProjectWBS();
        $list = $wbs->getList($args['id']);
        echo json_encode($list);
    });
    $this->post('', function($req, $res, $args) {
        $wbs = new ProjectWBS();
        $postBody = $req->getParsedBody();
        $postParams = $_GET;
        $params = array_merge($postBody, $postParams);
        $res = $wbs->saveWBS($params);
        echo json_encode($res);
    });
    $this->delete('/{id}', function($req, $res, $args) {
        $wbs = new ProjectWBS();
        $list = $wbs->delete_recursive($args['id']);
        echo json_encode(array('status' => 'DELETED ' . $args['id']));
    });
});
