<?php

require_once('KREST/handlers/module.php');


$app->get('/module/CampaignTasks/{campaignid}/items', function ($req, $res, $args) use ($app) {
    global $timedate;

    if (!ACLController::checkAccess('CampaignTasks', 'detail', true))
        throw ( new KREST\ForbiddenException("Forbidden for details in module CampaignTasks."))->setErrorCode('noModuleDetails');

    $getParams = $_GET;
    $now = $timedate->nowDb();
    $campaignLog = BeanFactory::getBean('CampaignLog');
    $list = $campaignLog->get_list(
        "planned_activity_date DESC",
        "campaigntask_id = '{$args['campaignid']}' AND IFNULL(planned_activity_date, '$now') <= '$now' AND activity_type != 'completed'",
        $getParams['offset'] ?: 0,
        $getParams['limit'] ?: 10,
        $getParams['limit'] ?: -1);

    // get a KREST Handler
    $KRESTModuleHandler = new KRESTModuleHandler($app);

    // empty items structure for the return
    $items = [];

    foreach ($list['list'] as $item) {
        $seed = BeanFactory::getBean($item->target_type, $item->target_id);
        $items[] = array(
            'campaignlog_id' => $item->id,
            'campaignlog_activity_type' => $item->activity_type,
            'campaignlog_activity_date' => $item->activity_date,
            'campaignlog_related_id' => $item->related_id,
            'campaignlog_planned_activity_date' => $item->planned_activity_date,
            'campaignlog_target_type' => $item->target_type,
            'campaignlog_hits' => $item->hits,
            // tbd
            'data' => $KRESTModuleHandler->mapBeanToArray($item->target_type, $seed)
        );
    }

    echo json_encode(array('items' => $items, 'row_count' => $list['row_count']));
});

$app->post('/module/CampaignTasks/{campaignid}/activate', function ($req, $res, $args) use ($app) {

    // ACL Check
    if (!ACLController::checkAccess('CampaignTasks', 'edit', true))
        throw ( new KREST\ForbiddenException("Forbidden to edit in module CampaignTasks."))->setErrorCode('noModuleEdit');

    // load the campaign task
    $campaignTask = BeanFactory::getBean('CampaignTasks', $args['campaignid']);

    // activate the campaigntask
    $campaignTask->activate();

    echo json_encode(array('success' => true, 'id' => $args['campaignid']));
});


$app->post('/module/CampaignTasks/{campaigntaskid}/sendtestmail', function($req, $res, $args) use ($app) {
    $campaignTask = BeanFactory::getBean('CampaignTasks',$args['campaigntaskid']);
    echo json_encode(array('success' => $campaignTask->sendTestEmail()));
});

$app->post('/module/CampaignTasks/{campaigntaskid}/queuemail', function($req, $res, $args) use ($app) {
    global $db, $current_user;
    $campaignTask = BeanFactory::getBean('CampaignTasks',$args['campaigntaskid']);
    $campaignTask->activate('queued');
    echo json_encode(array('success' => true));
});