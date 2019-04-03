<?php

$app->group('/OutputTemplates', function() {
    $this->get('/{id}/compile/{bean_id}', function($req, $res, $args) {
        $bean = BeanFactory::getBean('OutputTemplates', $args['id']);
        $bean->bean_id = $args['bean_id'];
        return json_encode(['content' => $bean->translateBody()]);
    });
    $this->get('/{id}/convert/{bean_id}/to/{format}', function($req, $res, $args) {
        $bean = BeanFactory::getBean('OutputTemplates', $args['id']);
        $bean->bean_id = $args['bean_id'];
        $file = $bean->getPdfContent();
        return $res->withHeader('Content-Type', 'application/pdf')->write($file);
    });
    $this->get('/{id}/convert/{bean_id}/to/{format}/base64', function($req, $res, $args) {
        $bean = BeanFactory::getBean('OutputTemplates', $args['id']);
        $bean->bean_id = $args['bean_id'];
        $file = $bean->getPdfContent();
        return json_encode(['content' => base64_encode($file)]);
    });
});