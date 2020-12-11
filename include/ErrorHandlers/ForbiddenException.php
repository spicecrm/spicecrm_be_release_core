<?php
namespace SpiceCRM\includes\ErrorHandlers;

class ForbiddenException extends Exception {

    protected $isFatal = false;
    protected $httpCode = 403;

    function __construct( $message = null, $errorCode = null ) {
        if ( !isset( $message )) $this->lbl = 'ERR_HTTP_FORBIDDEN';
        parent::__construct( isset( $message ) ? $message : 'Forbidden', $errorCode );
    }

    protected function extendResponseData() {
        $this->responseData['currentUser'] = @$GLOBALS['current_user']->user_name;
    }

}
