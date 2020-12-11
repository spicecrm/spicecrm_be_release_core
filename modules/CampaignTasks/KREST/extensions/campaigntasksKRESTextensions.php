<?php
use SpiceCRM\modules\CampaignTasks\KREST\controllers\CampaignTasksKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->get('/module/CampaignTasks/{campaignid}/items', [new CampaignTasksKRESTController(), 'getCampaignTaskItems']);
$RESTManager->app->post('/module/CampaignTasks/{campaigntaskid}/activate', [new CampaignTasksKRESTController(), 'activateCampaignTask']);
$RESTManager->app->post('/module/CampaignTasks/{campaignid}/export', [new CampaignTasksKRESTController(), 'exportCampaignTask']);
$RESTManager->app->post('/module/CampaignTasks/{campaigntaskid}/sendtestmail',[new CampaignTasksKRESTController(), 'sendCampaignTaskTestEmail']);
$RESTManager->app->post('/module/CampaignTasks/{campaigntaskid}/queuemail',[new CampaignTasksKRESTController(), 'queueCampaignTaskEmail']);
$RESTManager->app->post('/CampaignTasks/liveCompile/{module}/{parent}',[new CampaignTasksKRESTController(), 'liveCompileEmailBody']);


$RESTManager->app->group('/module/CampaignTasks/export', function () {
    $this->get('/reports', [new CampaignTasksKRESTController(), 'getExportReports']);
});
