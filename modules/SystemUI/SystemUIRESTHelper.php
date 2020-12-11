<?php
namespace SpiceCRM\modules\SystemUI;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;

class SystemUIRESTHelper{
    static function checkAdmin()
    {
        if (!$GLOBALS['current_user']->is_admin)
            // set for cors
            // header("Access-Control-Allow-Origin: *");
            throw ( new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
    }
}
