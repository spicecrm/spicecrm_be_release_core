<?php
namespace SpiceCRM\modules\SystemDeploymentPackages\api\controllers;

use SpiceCRM\data\BeanFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use SpiceCRM\includes\SpiceSlim\SpiceResponse as Response;

class SystemDeploymentPackageController{

    /**
     * get SystemDeploymentPackages list
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */

    public function getDeploymentPackageList($req,$res,$args){
        $rp = BeanFactory::getBean('SystemDeploymentPackages');
        $getParams = $req->getQueryParams();
        $list = $rp->getList($getParams);
        return $res->withJson($list);

    }

    /**
     * saves SystemDeploymentPackages
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function saveRPPackages(Request $req, Response $res, array $args): Response {
        $rp = BeanFactory::getBean('SystemDeploymentPackages');
        $postBody = $req->getParsedBody();
        $postParams = $req->getQueryParams();
        $params = array_merge($postBody, $postParams);
        $res = $rp->saveRP($params);
        return $res->withJson($res);
    }

//    /**
//     * mark packages as deleted
//     * @param $req
//     * @param $res
//     * @param $args
//     * @return mixed
//     */
//     public function MarkPackagesDeleted($req,$res,$args){
//        $rp = BeanFactory::getBean('SystemDeploymentPackages');
//        $rp->retrieve($args['id']);
//        $list = $rp->mark_deleted($args['id']);
//        return $res->withJson(['status' => 'OK']);
//    }

    /**
     * maps the status with a package
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function getStatus(Request $req, Response $res, array $args): Response {
        $list = [];
        $app_list_strings['rpstatus_dom'] = [
            '0' => 'created',
            '1' => 'in progress',
            '2' => 'completed',
        ];
        foreach ($app_list_strings['rpstatus_dom'] as $id => $name) {
            $list[] = [
                'id' => $id,
                'name' => $name
            ];
        }
        return $res->withJson(['list' => $list]);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function getTypes(Request $req, Response $res, array $args): Response {
        $app_list_strings = return_app_list_strings_language("en_us");
        $list = [];
        foreach ($app_list_strings['rptype_dom'] as $id => $name) {
            if($id === '4') continue; // type imported only over upload in deployment manager
            $list[] = [
                'id' => $id,
                'name' => $name
            ];
        }
        return $res->withJson(['list' => $list]);
    }

    /**
     * get the SystemDeploymentPackages CRs
     * having crstatus 3
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
     public function getCRs(Request $req, Response $res, array $args): Response {
        $getParams = $req->getQueryParams(); // should have some searchterm / or a filter to build where clause crstatus=3 in getCRs()
        $getParams['id'] = $args['id'];
        $rp = BeanFactory::getBean('SystemDeploymentPackages');
        $files = $rp->getCRs($getParams);
        return $res->withJson($files);
    }

    /**
     * gets the cr list
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
     public function getCRList(Request $req, Response $res, array $args): Response {
        $getParams = $req->getQueryParams();
        $rp = BeanFactory::getBean('SystemDeploymentPackages');
        $files = $rp->getCRList($getParams);
        return $res->withJson($files);
    }

//    /**
//     * packages the SystemDeploymentPackages
//     * @param $req
//     * @param $res
//     * @param $args
//     * @return mixed
//     */
//
//    public function KPackage($req,$res,$args){
//        $getParams = $req->getQueryParams();
//        $rp = BeanFactory::getBean('SystemDeploymentPackages');
//        $files = $rp->package($getParams); // method doesn't exist!
//        return $res->withJson($files);
//    }

    /**
     * release the SystemDeploymentPackages
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function releasePackage(Request $req, Response $res, array $args): Response {
        $rp = BeanFactory::getBean('SystemDeploymentPackages');
        $files = $rp->release_package($args['id']);
        return $res->withJson(['status' => 'RELEASED '.$args['id']]);
    }

    /**
     * get the bundles of packages for installer
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function getPackagesForInstall(Request $req, Response $res, array $args): Response {
        // @todo: select content from database
        $languages = ['en_us', 'de_DE'];
        $packages = ['spicecore' => ['core', 'aclessentials', 'ftsreference']];
        return $res->withJson(['languages' => $languages, 'packages' => $packages]);
    }

}