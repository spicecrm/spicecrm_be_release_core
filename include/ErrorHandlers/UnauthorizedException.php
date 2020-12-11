<?php
namespace SpiceCRM\includes\ErrorHandlers;

class UnauthorizedException extends Exception {

    protected $isFatal = false;
    protected $httpCode = 401;

    function __construct( $message = null, $errorCode = null ) {
        if ( !isset( $message )) $this->lbl = 'ERR_HTTP_NO_AUTHORIZATION';
        parent::__construct( isset( $message ) ? $message : 'No Authorization', $errorCode );
    }

}
