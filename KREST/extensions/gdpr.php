<?php


$KRESTModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler($app);

$KRESTManager->registerExtension('gdpr', '1.0');

$app->group('/gdpr', function () use ($app, $KRESTManager, $KRESTModuleHandler) {

    $app->get('/{module}/{id}', function ($req, $res, $args) use ($app, $KRESTModuleHandler) {
        $seed = BeanFactory::getBean($args['module'], $args['id']);
        if(!$seed){
            throw new \SpiceCRM\KREST\NotFoundException();
        }

        if(!$seed->ACLAccess('detail')){
            throw new \SpiceCRM\KREST\ForbiddenException();
        }

        if(method_exists($seed, 'getGDPRRelease')){
            return json_encode($seed->getGDPRRelease());
        } else {
            return json_encode([]);
        }
    });
});