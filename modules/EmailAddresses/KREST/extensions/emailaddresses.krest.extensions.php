<?php

require_once('modules/EmailAddresses/EmailAddress.php');

$app->post('/EmailAddresses/{searchterm}', function ($req, $res, $args) use ($app, $ftsManager) {
    $emailAddress = new EmailAddress();
    echo json_encode($emailAddress->search($args['searchterm']));
});