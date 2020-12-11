<?php
require_once 'modules/Campaigns/utils.php';
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/newsletters', function () {
    $this->get('/subscriptions/{contactid}', function($req, $res, $args) {
        $focus = BeanFactory::getBean('Contacts', $args['contactid']);
        $subscription_arrays = get_subscription_lists_query($focus, true);
        echo json_encode($subscription_arrays);
    });
    $this->post('/subscriptions/{contactid}', function($req, $res, $args) {
        $postBody = json_decode($_POST, true);
        $postParams = $_GET;
        $focus = BeanFactory::getBean('Contacts', $args['contactid']);
        foreach($postBody['subscribed'] as $subscribed){
            subscribe($subscribed['id'], '', $focus, true);
        }
        foreach($postBody['unsubscribed'] as $unsubscribed){
            unsubscribe($unsubscribed['id'], $focus);
        }
        echo json_encode(array('status' => 'success'));
    });
});
