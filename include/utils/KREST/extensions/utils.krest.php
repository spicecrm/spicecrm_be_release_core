<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('utils', '1.0');
$restapp = $RESTManager->app;

$restapp->group('/pdf', function () {
    $this->group('/toImage', function () {
        $this->get('/base64data/{filepath}', function($req, $res, $args) {
            $RESTUtilsHandler = new \SpiceCRM\includes\utils\controllers\RESTUtilsController();
            $data = $RESTUtilsHandler->pdfToBase64Image($args['filepath']);
            return $res->withJson($data);
        });
        $this->get('/url/{filepath}', function($req, $res, $args) {
            $RESTUtilsHandler = new \SpiceCRM\includes\utils\controllers\RESTUtilsController();
            $urls = $RESTUtilsHandler->pdfToUrlImage($args['filepath']);
            return $res->withJson($urls);
        });
    });
    $this->group('/upload', function () {
        $this->post('/tmp', function ($req, $res, $args) {
            $postBody = $req->getParsedBody();
            $temppath = sys_get_temp_dir();
            $filename = create_guid() . '.pdf';
            file_put_contents($temppath . '/' . $filename, base64_decode($postBody));
            echo $temppath . '/' . $filename;
        });
        $this->post('/uploadsDir', function ($req, $res, $args) {
            global $sugar_config;
            $postBody = $req->getParsedBody();
            $filename = create_guid() . '.pdf';
            file_put_contents($sugar_config['upload_dir'] . $filename, base64_decode($postBody));
            echo $sugar_config['upload_dir'] . $filename;
        });
    });
});

