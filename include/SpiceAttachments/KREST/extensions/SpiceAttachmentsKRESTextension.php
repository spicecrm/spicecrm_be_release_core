<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceAttachments\SpiceAttachments;
use SpiceCRM\includes\SpiceAttachments\KREST\controllers\SpiceAttachmentsKRESTController;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/{beanName}/{beanId}/attachment', function () {
    $this->post('', function ($req, $res, $args) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        return json_encode(SpiceAttachments::saveAttachmentHashFiles($args['beanName'], $args['beanId'], array_merge($postBody, $postParams)));
    });
    $this->get('', function ($req, $res, $args) {
        echo SpiceAttachments::getAttachmentsForBeanHashFiles($args['beanName'], $args['beanId']);
    });
    $this->get('/count', function ($req, $res, $args) {
        return $res->withJson(['count' => SpiceAttachments::getAttachmentsCount($args['beanName'], $args['beanId'])]);
    });
    $this->delete('/{attachmentId}', function ($req, $res, $args) {
        echo SpiceAttachments::deleteAttachment($args['attachmentId']);
    });
    $this->post('/ui', function ($req, $res, $args) {
        /* for fielupload over $_FILE. used by theme */
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        echo SpiceAttachments::saveAttachment($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
    });
    $this->get('/ui', function ($req, $res, $args) {
        /* for get file url for theme, not file in base64 */
        echo SpiceAttachments::getAttachmentsForBean($args['beanName'], $args['beanId']);
    });
    $this->get('/{attachmentId}', function ($req, $res, $args) {
        /* for get file url for theme, not file in base64 */
        echo SpiceAttachments::getAttachment($args['attachmentId']);
    });
    $this->get('/{attachmentId}/download', function ($req, $res, $args) {
        /* for get file url for theme, not file in base64 */
        echo SpiceAttachments::downloadAttachment($args['attachmentId']);
    });


});

/**
 * cleaned up the rest extension
 */
$RESTManager->app->group('/spiceAttachments', function () {
    $this->post('', [new SpiceAttachmentsKRESTController(), 'saveAttachment']);
    $this->group('/module/{beanName}/{beanId}', function () {
        $this->get('', [new SpiceAttachmentsKRESTController(), 'getAttachments']);
        $this->get('/count', [new SpiceAttachmentsKRESTController(), 'getAttachmentsCount']);
        $this->post('', [new SpiceAttachmentsKRESTController(), 'saveAttachments']);
        $this->get('/{attachmentId}', [new SpiceAttachmentsKRESTController(), 'getAttachment']);
        $this->get('/byfield/{fieldprefix}', [new SpiceAttachmentsKRESTController(), 'getAttachmentForField']);
        $this->delete('/{attachmentId}', [new SpiceAttachmentsKRESTController(), 'deleteAttachment']);
        $this->post('/clone/{fromBeanName}/{fromBeanId}', [new SpiceAttachmentsKRESTController(), 'cloneAttachments']);
    });
    $this->get('/categories/{module}', function ($req, $res, $args) {
        return json_encode(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachmentCategories($args['module']));
    });
    $this->group('/admin', function (){
        $this->get('', [new SpiceAttachmentsKRESTController(), 'getAnalysis']);
        $this->post('/cleanup', [new SpiceAttachmentsKRESTController(), 'cleanErroneous']);
    });
    $this->post('/{id}', function ($req, $res, $args) {
        return json_encode(SpiceAttachments::updateAttachmentData($args['id'], $req->getParsedBody()));
    });
});

