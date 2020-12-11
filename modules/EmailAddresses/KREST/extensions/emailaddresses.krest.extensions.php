<?php
use SpiceCRM\modules\EmailAddresses\EmailAddressRestHandler;
use EmailAddress;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->post('/EmailAddresses/{searchterm}', function ($req, $res, $args) {
    $emailAddress = new EmailAddress();
    echo json_encode($emailAddress->search($args['searchterm']));
});

$RESTManager->app->post('/EmailAddress/searchBeans', function ($req, $res, $args) {
    $result = EmailAddressRestHandler::searchBeans($req->getParsedBody());
    echo json_encode($result);
});
