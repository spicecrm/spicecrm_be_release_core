<?php

$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('gdpr', '2.0');

$KRESTModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler($RESTManager->app);

$RESTManager->app->group('/gdpr', function () use ($RESTManager, $KRESTModuleHandler) {

    $this->get('/{module}/{id}', function ($req, $res, $args) use ($KRESTModuleHandler) {
        $seed = BeanFactory::getBean($args['module'], $args['id']);
        if(!$seed){
            throw new \SpiceCRM\includes\ErrorHandlers\NotFoundException();
        }

        if(!$seed->ACLAccess('detail')){
            throw new \SpiceCRM\includes\ErrorHandlers\ForbiddenException();
        }

        if(method_exists($seed, 'getGDPRRelease')){
            return json_encode($seed->getGDPRRelease());
        } else {
            return json_encode([]);
        }
    });

    /*
     * Get the GDPR consent text for portal user from the CRM configuration.
     */
    $this->get('/portalGDPRconsentText', [new SpiceCRM\includes\SugarObjects\KREST\controllers\gdprController(), 'getPortalGDPRconsentText']);

    /*
     * Saves the GDPR consent of a portal user.
     */
    $this->post('/portalGDPRconsent', [new SpiceCRM\includes\SugarObjects\KREST\controllers\gdprController(), 'setPortalGDPRconsent']);

});
