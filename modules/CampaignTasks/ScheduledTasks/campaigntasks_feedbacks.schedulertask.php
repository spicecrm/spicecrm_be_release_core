<?php


$job_strings[] = 'sendCampaignTaskFeedbacks';

function sendCampaignTaskFeedbacks()
{
    $campaignTask = BeanFactory::getBean('CampaignTasks');
    return $campaignTask->genereateServiceFeedbacks();
}

