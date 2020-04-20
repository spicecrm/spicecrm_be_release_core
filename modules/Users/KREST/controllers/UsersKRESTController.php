<?php

namespace SpiceCRM\modules\Users\KREST\controllers;

use \SpiceCRM\KREST\BadRequestException;
use \SpiceCRM\KREST\ForbiddenException;
use \SpiceCRM\KREST\NotFoundException;
use \SpiceCRM\KREST\handlers\ModuleHandler;
use \User;

class UsersKRESTController {

    /**
     * save User
     */
    public function saveUser( $req, $res, $args ) {

        global $db;
        $params = $req->getParams();

        $email1 = $params['email1'];
        if (!empty($email1)) {
            $q = "select id from users where id in ( SELECT  er.bean_id AS id FROM email_addr_bean_rel er,
                email_addresses ea WHERE ea.id = er.email_address_id
                AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = 'Users' AND email_address_caps IN ('{$email1}') )";

        $row = $db->fetchByAssoc($db->query($q));

        if ($row && $row['id'] != $params['id'])
            throw (new BadRequestException("Email already exists."))->setErrorCode('duplicateEmail1');

        $email1 = htmlspecialchars(stripslashes(trim($params['email1'])));
        if (!filter_var($email1, FILTER_VALIDATE_EMAIL))
            throw (new BadRequestException("Invalid email format."))->setErrorCode('invalidEmailFormat');
        }

        $KRESTModuleHandler = new ModuleHandler();
        $beanResponse = $KRESTModuleHandler->add_bean("Users", $args['id'], $params);

        return $res->withJson($beanResponse);

    }

    /**
     * setUserInactive
     *
     * Sets a user inactive.
     * You should know: Normally a user should not be deleted. Instead: Set to inactive.
     */
    public function setUserInactive( $req, $res, $args ) {

        if (!$GLOBALS['ACLController']->checkAccess( 'Users', 'edit', true ))
            throw (new ForbiddenException("Forbidden to edit in module Users."))->setErrorCode('noModuleEdit');

        $user = new User();
        $user->retrieve( $args['id'] );
        if (!isset( $user->id )) throw (new NotFoundException('Record not found.'))->setLookedFor(['id' => $args['id'], 'module' => 'Users']);

        if ( !$user->ACLAccess('edit') )
            throw (new ForbiddenException('Forbidden to edit record.'))->setErrorCode('noRecordEdit');

        $user->status = 'Inactive';
        $user->save();

        return $res->withJson([ 'success' => true ]);

    }

}
