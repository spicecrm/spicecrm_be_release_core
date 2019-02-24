<?php

$app->group('/module/Emails/{id}', function () use ($app) {

    //$app->post('/module/Emails/{id}/setstatus/{status}', function ($req, $res, $args) use ($app) {
    $app->post('/setstatus/{status}', function ($req, $res, $args) use ($app) {

        // todo: check auf erlaubte stati
        $email = BeanFactory::getBean('Emails', $args['id']);
        if (!$email) {
            throw (new KREST\NotFoundException('Record not found.'))->setLookedFor(id);
        }

        if (!$email->ACLAccess('edit')) {
            throw (new KREST\ForbiddenException("Forbidden to edit Email."))->setErrorCode('noModuleEdit');
        }

        $email->status = $args['status'];
        $email->save();

        echo json_encode(array('status' => 'success'));

    });

    $app->post('/setopenness/{openness}', function ($req, $res, $args) use ($app) {

        $email = BeanFactory::getBean('Emails', $args['id']);
        if (!$email) {
            throw (new KREST\NotFoundException('Record not found.'))->setLookedFor(id);
        }

        if (!$email->ACLAccess('edit')) {
            throw (new KREST\ForbiddenException("Forbidden to edit Email."))->setErrorCode('noModuleEdit');
        }

        $email->openness = $args['openness'];
        $email->save();

        echo json_encode(array('status' => 'success'));

    });

    $app->get('/process', function ($req, $res, $args) use ($app) {
        $email = BeanFactory::getBean('Emails', $args['id']);
        if (!$email) {
            throw (new KREST\NotFoundException('Record not found.'))->setLookedFor(id);
        }

        $email->processEmail();        
    });
});
