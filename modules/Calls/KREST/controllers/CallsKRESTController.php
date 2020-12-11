<?php

namespace SpiceCRM\modules\Calls\KREST\controllers;

use SpiceCRM\includes\ErrorHandlers\ForbiddenException;

class CallsKRESTController
{

    static function setStatus($req, $res, $args)
    {
        global $db, $current_user, $timedate;

        // acl check if user can edit - must be adming or current user
        if (!$current_user->is_admin && $current_user->id != $args['userid'])
            throw (new ForbiddenException("only allowed for admins or assigned user"));

        // update directly on the db
        $db->query("UPDATE calls_users SET accept_status='{$args['status']}', date_modified='{$timedate->nowDb()}' WHERE deleted = 0 AND call_id='{$args['id']}' AND user_id='{$args['userid']}'");

        // CR1000356 re-index call
        if($bean = \BeanFactory::getBean('Calls', $args['id'])){
            $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
            $spiceFTSHandler->indexBean($bean);
        }

        // return
        return $res->withJson(['status' => 'success']);
    }
}
