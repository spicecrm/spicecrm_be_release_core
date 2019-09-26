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

require_once('KREST/handlers/module.php');

$KRESTModuleHandler = new KRESTModuleHandler($app);

$KRESTManager->registerExtension('metadata', '1.0');

$app->group('/metadata', function () use ($app, $KRESTModuleHandler) {
    $this->get('/modules', function() use ($app, $KRESTModuleHandler) {
        echo json_encode($KRESTModuleHandler->get_modules());
    });
    $this->get('/vardefs/{module}', function($req, $res, $args) use ($app, $KRESTModuleHandler) {
        $bean = BeanFactory::getBean($args['module']);
        echo json_encode($bean->field_name_map);
    });
});
