<?php

require_once('include/SugarQueue/SugarJobQueue.php');

$app->get('/module/Scheduler/jobslist', function ($req, $res, $args) use ($app) {
    return $res->withJson(Scheduler::getJobsList());
});
$app->post('/module/Scheduler/{sid}/runjob', function ($req, $res, $args) use ($app) {
    global $current_user;
    if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
    $scheduler = BeanFactory::getBean('Schedulers', $args['sid']);
    $job = $scheduler->createJob();
    ob_start();
    $jobStatus = $job->runJob(false);
    $job->completeJob($jobStatus);
    $result = ob_get_clean();
    echo(json_encode(array('results' => $result)));
});

$app->post('module/Scheduler/{sid}/schedulejob', function ($req, $res, $args) use ($app) {
    global $current_user, $timedate;
    if (!$current_user->is_admin) throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

    $sugarJobQueue = new SugarJobQueue();
    $scheduler = BeanFactory::getBean('Schedulers', $args['sid']);
    $scheduler->last_run = $timedate->nowDb();
    $scheduler->save();
    $job = $scheduler->createJob();
    return $res->withJson($sugarJobQueue->submitJob($job));
});
