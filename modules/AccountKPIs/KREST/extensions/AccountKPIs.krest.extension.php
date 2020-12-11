<?php
use SpiceCRM\includes\RESTManager;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->get('/module/AccountKPIs/{accountid}/getsummary', function ($req, $res, $args) {
    global $db;

    //todo .. load account to see if user has access rights
    $seed = BeanFactory::getBean('Accounts', $args['accountid']);

    $date = new DateTime();
    $yearTo = $_GET['yearto'] ?: $date->format('Y');

    $date->sub(new DateInterval('P4Y'));
    $yearFrom = $_GET['yearfrom'] ?: $date->format('Y');

    $retArray = array(
        'companycodes' => [],
        'accountkpis' => [],
    );

   $companyCodes = $db->query("SELECT * FROM companycodes WHERE deleted = 0");
   while($companyCode = $db->fetchByAssoc($companyCodes)){
       $retArray['companycodes'][] = $companyCode;
   }

    if($seed) {
        $results = $db->query("SELECT year, companycode_id, SUM(revenue) AS revenue FROM accountkpis WHERE account_id='{$args['accountid']}' AND year BETWEEN '$yearFrom' AND '$yearTo' GROUP BY year, companycode_id");
        while ($result = $db->fetchByAssoc($results)) {
            $retArray['accountkpis'][$result['companycode_id']][$result['year']] = $result['revenue'];
        }
    }
    return $res->withJson($retArray);
});
