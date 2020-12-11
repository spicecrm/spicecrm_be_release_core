<?php

namespace SpiceCRM\modules\Meetings\KREST\controllers;

use SpiceCRM\includes\ErrorHandlers\ForbiddenException;

class MeetingsKRESTController
{

    static function setStatus($req, $res, $args)
    {
        global $db, $current_user, $timedate;

        // acl check if user can edit - must be adming or current user
        if (!$current_user->is_admin && $current_user->id != $args['userid'])
            throw (new ForbiddenException("only allowed for admins or assigned user"));

        // update directly on the db
        $db->query("UPDATE meetings_users SET accept_status='{$args['status']}', date_modified='{$timedate->nowDb()}' WHERE deleted = 0 AND meeting_id='{$args['id']}' AND user_id='{$args['userid']}'");

        // CR1000356 re-index meeting
        if($bean = \BeanFactory::getBean('Meetings', $args['id'], ['encode' => false])){
            $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
            $spiceFTSHandler->indexBean($bean);

// for exchange: send status to exchange => setInvitationStatusOnExchange() doesn't work... access denied...
// No documentation found to make it work.... Removed for now
//            if($bean->load_relationship('users')){
//                if(class_exists('\SpiceCRM\includes\SpiceCRMExchange\ModuleHandlers\SpiceCRMExchangeMeetings')){
//                    // check if user has a calendar exchange subscription & Meetings to be synced
//                    $userexchangesyncconfig = new \SpiceCRM\includes\SpiceCRMExchange\Connectivity\SpiceCRMExchangeUserSyncConfig($current_user->id);
//                    $syncmeetings = false;
//                    $userconfig = $userexchangesyncconfig->getConfig();
//                    foreach($userconfig as $idx => $cnf){
//                        if($cnf['sysmodule_name'] != 'Meetings'){
//                            continue;
//                        }
//                        if($cnf['exchangesubscription']){
//                            $syncmeetings = true;
//                            break;
//                        }
//                    }
//
//                    // trigger set invitation status
//                    if($syncmeetings){
//                        file_put_contents('ews.log', '$cnf => '.print_r($cnf, true)."\n", FILE_APPEND);
//                        $exchangeMeetingHandler = new \SpiceCRM\includes\SpiceCRMExchange\ModuleHandlers\SpiceCRMExchangeMeetings($current_user, $bean, true);
//                        // overwrite connector with current user (else assigned user  of event will be used as impersonated user
//                        $exchangeMeetingHandler->connector = new \SpiceCRM\includes\SpiceCRMExchange\SpiceCRMExchangeConnector($current_user);
//                        $exchangeMeetingHandler->setInvitationStatusOnExchange($bean, $args['status']);
//                    }
//
//
//                }
//            }
        }

        // return
        return $res->withJson(['status' => 'success']);
    }
}
