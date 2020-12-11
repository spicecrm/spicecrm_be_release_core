<?php
/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/
require_once('include/SugarQueue/SugarJobQueue.php');
use Scheduler;
use BeanFactory;
use SugarJobQueue;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->get('/module/Schedulers/jobslist', function ($req, $res, $args) {
    return $res->withJson(Scheduler::getJobsList());
});
$RESTManager->app->post('/module/Schedulers/{sid}/runjob', function ($req, $res, $args) {
    global $current_user;
    if (!$current_user->is_admin) throw ( new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
    $scheduler = BeanFactory::getBean('Schedulers', $args['sid']);
    $job = $scheduler->createJob();
    ob_start();
    $jobStatus = $job->runJob(false);
    $job->completeJob($jobStatus);
    $result = ob_get_clean();
    echo(json_encode(array('results' => $result)));
});

$RESTManager->app->post('/module/Schedulers/{sid}/schedulejob', function ($req, $res, $args) {
    global $current_user, $timedate;
    if (!$current_user->is_admin) throw ( new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

    $sugarJobQueue = new SugarJobQueue();
    $scheduler = BeanFactory::getBean('Schedulers', $args['sid']);
    $scheduler->last_run = $timedate->nowDb();
    $scheduler->save();
    $job = $scheduler->createJob();
    return $res->withJson($sugarJobQueue->submitJob($job));
});
