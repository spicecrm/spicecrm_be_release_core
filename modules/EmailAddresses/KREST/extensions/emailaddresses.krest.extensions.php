<?php

require_once('modules/EmailAddresses/EmailAddress.php');

$app->post('/EmailAddresses/{searchterm}', function ($req, $res, $args) use ($app, $ftsManager) {
    $emailAddress = new EmailAddress();
    echo json_encode($emailAddress->search($args['searchterm']));
});

$app->post('/EmailAddress/searchBeans', function ($req, $res, $args) use ($app, $ftsManager) {
    $result = \SpiceCRM\modules\EmailAddresses\EmailAddressRestHandler::searchBeans($req->getParsedBody());
    echo json_encode($result);
});
