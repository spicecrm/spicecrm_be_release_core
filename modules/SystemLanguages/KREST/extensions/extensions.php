<?php
/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/
use SpiceCRM\modules\SystemLanguages\SystemLanguagesRESTHandler;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use SpiceCRM\includes\ErrorHandlers\BadRequestException;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$handler = new SystemLanguagesRESTHandler();

$RESTManager->app->group('/syslanguages', function () use ($handler) {
    $this->group('/labels', function() use ($handler) {
        $this->post('', function($req, $res, $args) use($handler) {
            $result = $handler->saveLabels($req->getParsedBody());
            echo json_encode($result);
        });
        $this->delete('/{id}/[{environment}]', function($req, $res, $args) use($handler) {
            // delete a specific label...
            $result = $handler->deleteLabel($args['id'], $args['environment']);
            echo json_encode($result);
        });
        $this->get('/search/{search_term}', function($req, $res, $args) use($handler) {
            $result = $handler->searchLabels($args['search_term']);
            echo json_encode($result);
        });
        $this->get('/{label_name}', function($req, $res, $args) use($handler) {
            $result = $handler->retrieveLabelDataByName($args['label_name']);
            echo json_encode($result);
        });
    });

    $this->group('/load', function() use ($handler) {
        $this->get('/{language}', function($req, $res, $args) use($handler) {
            $params = $_GET;
            $params['language'] = $args['language'];
            $result = $handler->loadSysLanguages($params);
            echo json_encode($result);

        });
    });

    $this->post('/setdefault/{language}', function($req, $res, $args) {
        global $db, $current_user;

        if(!$current_user->is_admin){
            return $res->write(json_encode(['success' => false]));
        }

        $db->query("UPDATE syslangs SET is_default = 0 WHERE is_default = 1");
        $db->query("UPDATE syslangs SET is_default = 1 WHERE language_code = '{$args['language']}'");

        return $res->write(json_encode(['success' => true]));

    });

    $this->post( '/filesToDB', function( $req, $res, $args ) use($handler) {

        if ( !$GLOBALS['current_user']->is_dev )
            throw (new ForbiddenException('No development privileges.'))->setErrorCode('notDeveloper');

        if ( $req->getParsedBodyParam('confirmed' ) !== true )
            throw (new BadRequestException('Operation not confirmed.'))->setErrorCode('notConfirmed');

        $result = $handler->transferFromFilesToDB( $args );
        if ( $result === false ) return $res->withJson(['success' => false]);
        else return $res->withJson( $result );

    });

});

$RESTManager->app->get('/syslanguage/{language}/{scope}/labels/untranslated', function($req, $res, $args) use($handler) {
    global $current_user;
    if (!$current_user->is_admin) {
        throw (new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
    }

    echo json_encode($handler->getUntranslatedLabels($args['language'], $args['scope']));

});
