<?php
namespace SpiceCRM\includes\SysTrashCan\KREST\controllers;

use SpiceCRM\includes\authentication\AuthenticationController;
use SpiceCRM\includes\SysTrashCan\SysTrashCan;
use Slim\Routing\RouteCollectorProxy;
use SpiceCRM\includes\SpiceSlim\SpiceResponse;
use Psr\Http\Message\RequestInterface;

class SysTrashCanRecoveryController{


    /**
     * get deleted records
     * @param $req RequestInterface
     * @param $res SpiceResponse
     * @param $args
     * @return mixed
     */

    public function getUserTrashRecords($req, $res, $args){
        $current_user = AuthenticationController::getInstance()->getCurrentUser();
        return $res->withJson(SysTrashCan::getRecords());
    }

    /**
     * get the related deleted records
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */

    public function getRelatedTrashRecords($req, $res, $args){
        $current_user = AuthenticationController::getInstance()->getCurrentUser();
        return $res->withJson(SysTrashCan::getRelated($args['transactionid'], $args['recordid']));

    }

    /**
     * recover thrashed beans
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */

    public function getRecoveredTrashRecords($req, $res, $args){
        $current_user = AuthenticationController::getInstance()->getCurrentUser();
        $requestData = $req->getQueryParams();
        $params = $req->getQueryParams();
        $recovery = SysTrashCan::recover($args['id'], $params['recoverrelated'] == '1' ? true : false);
        return $res->withJson([
            'status' => $recovery === true ? 'success' : 'error',
            'message' => $recovery === true ? '' : $recovery
        ]);
    }
}