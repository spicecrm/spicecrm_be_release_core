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
namespace SpiceCRM\includes\SysModuleFilters\KREST\controllers;

class SysModuleFiltersController
{

    function __construct()
    {

    }

    private function checkAdmin()
    {
        global $current_user;
        if (!$current_user->is_admin) {
            throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');

        }
    }

    function getFilters($req, $res, $args)
    {
        global $db;

        $this->checkAdmin();

        $filters = [];
        $filtersObj = "SELECT 'global' As `type`, fltrs.* FROM sysmodulefilters fltrs  WHERE fltrs.module = '{$args['module']}' UNION ";
        $filtersObj .= "SELECT 'custom' As `type`, cfltrs.* FROM syscustommodulefilters cfltrs  WHERE cfltrs.module = '{$args['module']}'";
        $filtersObj = $db->query($filtersObj);
        while ($filter = $db->fetchByAssoc($filtersObj))
            $filters[] = $filter;

        return $res->write(json_encode($filters));
    }

    function saveFilter($req, $res, $args)
    {
        global $db, $current_user;
        $this->checkAdmin();
        $filterdata = $req->getParsedBody();
        // check if filter exists
        $filter = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulefilters WHERE id='{$args['filter']}'"));
        if ($filter) {
            $filterdefs = json_encode($filterdata['filterdefs']);
            $db->query("UPDATE sysmodulefilters SET name='{$filterdata['name']}', filterdefs='$filterdefs', version='{$filterdata['version']}', package='{$filterdata['package']}' WHERE id = '{$args['filter']}'");
        } else {
            $filterdefs = json_encode($filterdata['filterdefs']);
            $db->query("INSERT INTO sysmodulefilters (id, created_by_id, module, name, filterdefs, version, package) VALUES('{$args['filter']}', '$current_user->id', '{$args['module']}', '{$filterdata['name']}', '$filterdefs', '{$filterdata['version']}', '{$filterdata['package']}')");
        }
    }

    function deleteFilter($req, $res, $args)
    {
        global $db;
        $this->checkAdmin();
        $id = $db->quote($args['filter']);
        $result = $db->query("DELETE FROM sysmodulefilters WHERE id = '$id'");
        return $res->withJson($result);
    }
}

