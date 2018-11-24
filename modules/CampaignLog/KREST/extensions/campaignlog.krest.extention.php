<?php

$KRESTManager->registerExtension('telecockpit', '1.0');

$app->post('/module/CampaignLog/{campaignlogid}/{status}', function ($req, $res, $args) use ($app) {
    global $timedate;
    // ACL Check
    /* todo: check what ACL we need to check
    if (!ACLController::checkAccess('CampaignTasks', 'edit', true)) {
        http_response_code(403);
        echo('not authorized for module ' . 'CampaignTasks');
        exit;
    }
    */

    $campaignLog = BeanFactory::getBean('CampaignLog', $args['campaignlogid']);

    $status = $args['status'];

    $postParams = $req->getParams();

    if ($campaignLog) {

        switch($status){
            case 'attempted':
                $campaignLog->planned_activity_date = $postParams['planned_activity_date'];
                $campaignLog->hits += 1;
                break;
            case 'called':
                $campaignLog->related_id = $postParams['call_id'];
                $campaignLog->related_type = 'Calls';
                $campaignLog->hits += 1;
                $campaignLog->planned_activity_date = undefined;
                break;
        }

        $campaignLog->activity_type = $status;
        $campaignLog->activity_date = $timedate->nowDb();
        $campaignLog->save();

        echo json_encode(array('success' => true, 'id' => $args['campaignlogid']));
    } else {
        echo json_encode(array('success' => false));
    }
});