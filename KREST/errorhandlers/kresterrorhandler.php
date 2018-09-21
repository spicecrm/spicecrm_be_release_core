<?php
/*
 * ERROR HANDLING
 * each thrown Exception is catched here and is available in $exception
 */

require_once 'KREST/handlers/exceptionClasses.php';

$c = $app->getContainer();

// Error handlers

# errorHandler is for PHP < 7
$c['errorHandler'] = $c['phpErrorHandler'] = function( $container ) {
    return function( $request, $response, $exceptionObjectOrMessage ) {
        outputError( $exceptionObjectOrMessage );
    };
};

$c['notFoundHandler'] = function ( $container ) {
    return function( $request, $response ) {
        outputError( new KREST\NotFoundException());
    };
};

$c['notAllowedHandler'] = function( $container ) {
    return function ( $request, $response, $allowedMethods ) use( $container ) {
        $responseData['error'] = [ 'message' => 'Method not allowed.', 'errorCode' => 'notAllowed', 'methodsAllowed' => implode(', ', $allowedMethods), 'httpCode' => 405 ];
        return $container['response']
            ->withHeader('Allow', implode(', ', $allowedMethods )) # todo: header not appears in browser (response)
            ->withJson( $responseData, 405 );
    };
};


function outputError( $exception ) {

    $inDevMode = isset( $GLOBALS['sugar_config']['developerMode'] ) and $GLOBALS['sugar_config']['developerMode'];

    if ( is_object( $exception )) {

        if ( is_a( $exception, 'KREST\Exception' ) ) {
            if ( $exception->isFatal() ) $GLOBALS['log']->fatal( $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine() );
            $responseData = $exception->getResponseData();
            if ( get_class( $exception ) === 'KREST\Exception' ) {
                $responseData['line'] = $exception->getLine();
                $responseData['file'] = $exception->getFile();
                $responseData['trace'] = $exception->getTrace();
            }
            $httpCode = $exception->getHttpCode();
        } else {
            if ( $inDevMode )
                $responseData =  [ 'message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace() ];
            else $responseData['error'] = ['message' => 'Application Error.'];
            $httpCode = $exception->getCode();
        }

    } else {

        $GLOBALS['log']->fatal( $exception );
        $responseData['error'] = [ 'message' => $inDevMode ? 'Application Error.' : $exception ];
        $httpCode = 500;

    }

    http_response_code( $httpCode ? $httpCode : 500 );
    echo json_encode( [ 'error' => $responseData ]);
    exit;

}





$app->add( function( $request, $response, $next ) {
    try {
        $response = $next( $request, $response );
    }
    catch( KREST\Exception $exception ) {
        outputError( $exception );
    }
    catch( Exception $exception ) {
        outputError( $exception );
    }
    return $response;
});
