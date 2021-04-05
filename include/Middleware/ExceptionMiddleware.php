<?php
namespace SpiceCRM\includes\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use SpiceCRM\includes\ErrorHandlers\Exception;
use SpiceCRM\includes\Logger\LoggerManager;
use SpiceCRM\includes\SugarObjects\SpiceConfig;

class ExceptionMiddleware extends FailureMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (Exception $exception) {
            return $this->handleSpiceException($exception);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Handles SpiceCRM internal exceptions.
     * Returns the code, error and location.
     *
     * @param Exception $exception
     * @return ResponseInterface
     */
    private function handleSpiceException(Exception $exception): ResponseInterface {
        if ($exception->isFatal()) {
            LoggerManager::getLogger()->fatal($exception->getMessageToLog() . ' in ' . $exception->getFile()
                . ':' . $exception->getLine() );
        }
        $responseData = $exception->getResponseData();
        // todo does this if even make sense at this point?
        if (get_class($exception) === Exception::class) {
            $responseData['line']  = $exception->getLine();
            $responseData['file']  = $exception->getFile();
            $responseData['trace'] = $exception->getTrace();
        }
        $httpCode = $exception->getHttpCode();
        $specialResponseHeaders = $exception->getHttpHeaders();

        return $this->generateResponse($responseData, $httpCode, $specialResponseHeaders);
    }

    /**
     * Handles all other types of exceptions.
     * Returns the error info only if the developer mode is enabled.
     *
     * @param \Exception $exception
     * @return ResponseInterface
     */
    private function handleException(\Exception $exception): ResponseInterface {
        $inDevMode = SpiceConfig::getInstance()->config['developerMode'];

        if ($inDevMode) {
            $responseData = [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
                'trace'   => $exception->getTrace(),
            ];
        } else {
            $responseData['error'] = ['message' => 'Application Error.'];
        }
        // todo does it have to be always 500?
        $httpCode = $exception->getCode() ?: 500;

        return $this->generateResponse($responseData, $httpCode);
    }
}
