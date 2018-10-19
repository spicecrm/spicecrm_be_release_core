<?php

require_once('KREST/handlers/module.php');

$KRESTModuleHandler = new KRESTModuleHandler($app);

$app->post('/module/Users/{id}', function ($req, $res, $args) use ($app, $KRESTModuleHandler) {
    global $db;
    $params = $req->getParams();

    $email1 = strtoupper($params['email1']);
    $q = "select id from users where id in ( SELECT  er.bean_id AS id FROM email_addr_bean_rel er,
			email_addresses ea WHERE ea.id = er.email_address_id
		    AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = 'Users' AND email_address_caps IN ('{$email1}') )";

    $row = $db->fetchByAssoc($db->query($q));

    if ($row && $row['id'] != $params['id'])
        throw ( new KREST\BadRequestException("Email already exists."))->setErrorCode('duplicateEmail1');

    $email1 = htmlspecialchars(stripslashes(trim($params['email1'])));
    if (!filter_var($email1, FILTER_VALIDATE_EMAIL))
        throw ( new KREST\BadRequestException("Invalid email format."))->setErrorCode('invalidEmailFormat');


    $beanResponse = $KRESTModuleHandler->add_bean("Users", $args['id'], $params);

    return $res->withJson($beanResponse);
});