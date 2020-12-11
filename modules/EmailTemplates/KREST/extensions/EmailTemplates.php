<?php
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/EmailTemplates', function () {
    $this->get('/{module}', function($req, $res, $args) {
        global $db;

        $template_list = array();

        $res = $db->query("SELECT id, name FROM email_templates
                WHERE type = 'bean2mail' AND (for_bean = '{$args['module']}' OR for_bean = '*' )");
        while($row = $db->fetchByAssoc($res)) $template_list[] = $row;

        echo json_encode($template_list);
    });
    $this->get('/parse/{id}/{module}/{parent}', function($req, $res, $args) {
        global $app_list_strings, $current_language, $current_user;

        $app_list_strings = return_app_list_strings_language($current_language);

        $return = array(
            'name' => '',
            'description_html' => ''
        );
        $tpl = BeanFactory::getBean("EmailTemplates",$args['id']);
        $bean = BeanFactory::getBean($args['module'], $args['parent']);
        $parsedTpl = $tpl->parse($bean);

        echo json_encode(array(
            'subject' => $parsedTpl['subject'],
            'body_html' => from_html(wordwrap($parsedTpl['body_html'], true)),
            'body' => $parsedTpl['body']
        ));
    });
    $this->post('/liveCompile/{module}/{parent}', function($req, $res, $args) {
        $params = $req->getParsedBody();
        $emailTemplate = BeanFactory::getBean("EmailTemplates");
        $emailTemplate->body_html = $params['html'];
        $bean = BeanFactory::getBean($args['module'], $args['parent']);
        $parsedTpl = $emailTemplate->parse($bean);

        return $res->withJson(['html' => from_html(wordwrap($parsedTpl['body_html'], true))]);
    });
});
