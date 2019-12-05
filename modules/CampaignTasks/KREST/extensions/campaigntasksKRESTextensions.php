<?php


require_once('KREST/handlers/module.php');

$app->get('/module/CampaignTasks/{campaignid}/items', [new \SpiceCRM\modules\CampaignTasks\KREST\controllers\CampaignTasksKRESTController(), 'getCampaignTaskItems']);
$app->post('/module/CampaignTasks/{campaignid}/activate', [new \SpiceCRM\modules\CampaignTasks\KREST\controllers\CampaignTasksKRESTController(), 'activateCampaignTask']);
$app->post('/module/CampaignTasks/{campaignid}/export', [new \SpiceCRM\modules\CampaignTasks\KREST\controllers\CampaignTasksKRESTController(), 'exportCampaignTask']);
$app->post('/module/CampaignTasks/{campaigntaskid}/sendtestmail',[new \SpiceCRM\modules\CampaignTasks\KREST\controllers\CampaignTasksKRESTController(), 'sendCampaignTaskTestEmail']);
$app->post('/module/CampaignTasks/{campaigntaskid}/queuemail',[new \SpiceCRM\modules\CampaignTasks\KREST\controllers\CampaignTasksKRESTController(), 'queueCampaignTaskEmail']);