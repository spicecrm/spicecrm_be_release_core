<?php
namespace SpiceCRM\modules\SystemUI;

class SystemUIRESTHelper{
    static function checkAdmin()
    {
        if (!$GLOBALS['current_user']->is_admin)
            // set for cors
            // header("Access-Control-Allow-Origin: *");
            throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
    }
}