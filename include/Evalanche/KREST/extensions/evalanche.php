<?php
use SpiceCRM\includes\Evalanche\KREST\controllers\EvalancheController;

$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('evalanche', '1.0');

$RESTManager->app->group('/Evalanche', function () {
    $this->get('/CampaignTasks/{id}/templates', [new EvalancheController(), 'getTemplates']);
    $this->get('/CampaignTasks/{id}/report', [new EvalancheController(), 'getMailingStats']);
    $this->post('/CampaignTasks/{id}/sendmailing', [new EvalancheController(), 'sendMailing']);
    $this->post('/ProspectLists/{id}/sync', [new EvalancheController(), 'synchronizeTargetLists']);
    $this->post('/ProspectLists/{id}/stats', [new EvalancheController(), 'getProspectListStatistic']);
    $this->post('/CampaignTasks/{id}/sync', [new EvalancheController(), 'campaignTaskToEvalanche']);
});
