<?php


$KRESTModuleHandler = new KRESTModuleHandler($app);
$KRESTManager->registerExtension('gdpr', '1.0');

$app->group('/gdpr', function () use ($app, $KRESTManager, $KRESTModuleHandler) {

    $app->get('/{module}/{id}', function ($req, $res, $args) use ($app, $KRESTModuleHandler) {
        $seed = BeanFactory::getBean($args['module'], $args['id']);
        if(!$seed){
            throw new KREST\NotFoundException();
        }

        if(!$seed->ACLAccess('detail')){
            throw new KREST\ForbiddenException();
        }

        if(method_exists($seed, 'getGDPRRelease')){
            return json_encode($seed->getGDPRRelease());
        } else {
            return json_encode([]);
        }
    });
});