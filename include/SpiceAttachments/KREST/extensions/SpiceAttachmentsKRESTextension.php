<?php
$app->group('/module/{beanName}/{beanId}/attachment', function () use ($app) {
    $app->post('', function ($req, $res, $args) use ($app) {
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        return json_encode(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::saveAttachmentHashFiles($args['beanName'], $args['beanId'], array_merge($postBody, $postParams)));
    });
    $app->get('', function ($req, $res, $args) use ($app) {
        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachmentsForBeanHashFiles($args['beanName'], $args['beanId']);
    });
    $app->get('/count', function ($req, $res, $args) use ($app) {
        return $res->withJson(['count' => \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachmentsCount($args['beanName'], $args['beanId'])]);
    });
    $app->delete('/{attachmentId}', function ($req, $res, $args) use ($app) {
        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::deleteAttachment($args['attachmentId']);
    });
    $app->post('/ui', function ($req, $res, $args) use ($app) {
        /* for fielupload over $_FILE. used by theme */
        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::saveAttachment($args['beanName'], $args['beanId'], array_merge($postBody, $postParams));
    });
    $app->get('/ui', function ($req, $res, $args) use ($app) {
        /* for get file url for theme, not file in base64 */
        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachmentsForBean($args['beanName'], $args['beanId']);
    });
    $app->get('/{attachmentId}', function ($req, $res, $args) use ($app) {
        /* for get file url for theme, not file in base64 */
        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachment($args['attachmentId']);
    });
    $app->get('/{attachmentId}/download', function ($req, $res, $args) use ($app) {
        /* for get file url for theme, not file in base64 */
        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::downloadAttachment($args['attachmentId']);
    });
});
