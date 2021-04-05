<?php
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
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
********************************************************************************/

$GLOBALS['dictionary']['UserPreference'] = [
    'table' => 'user_preferences',
    'fields' => [
        'id' =>
            [
                'name' => 'id',
                'vname' => 'LBL_NAME',
                'type' => 'id',
                'required' => true,
                'reportable' => false,
            ],
        'category' =>
            [
                'name' => 'category',
                'type' => 'varchar',
                'len' => 50,
            ],
        'deleted' =>
            [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => '0',
                'required' => false,
            ],
        'date_entered' =>
            [
                'name' => 'date_entered',
                'type' => 'datetime',
                'required' => true,
            ],
        'date_modified' =>
            [
                'name' => 'date_modified',
                'type' => 'datetime',
                'required' => true,
            ],
        'assigned_user_id' =>
            [
                'name' => 'assigned_user_id',
                'rname' => 'user_name',
                'id_name' => 'assigned_user_id',
                'type' => 'assigned_user_name',
                'table' => 'users',
                'required' => true,
                'dbType' => 'id',
            ],
        'assigned_user_name' =>
            [
                'name' => 'assigned_user_name',
                'vname' => 'LBL_ASSIGNED_TO',
                'type' => 'varchar',
                'reportable' => false,
                'massupdate' => false,
                'source' => 'non-db',
                'table' => 'users',
            ],
        'contents' =>
            [
                'name' => 'contents',
                'type' => 'longtext',
                'vname' => 'LBL_DESCRIPTION',
                'isnull' => true,
            ],
    ],
    'indices' => [
        ['name' =>'userpreferencespk', 'type' =>'primary', 'fields'=>['id']],
        ['name' =>'idx_userprefcat', 'type'=>'index', 'fields'=>['category']],
        ['name' =>'idx_userprefnamecat', 'type'=>'index', 'fields'=>['assigned_user_id','category']],
        ['name' =>'idx_userprefnamecatdel', 'type'=>'index', 'fields'=>['assigned_user_id','category', 'deleted']],
    ]
];
 



//// cn: bug 12036 - $dictionary['x'] for SugarBean::createRelationshipMeta() from upgrades
//$dictionary['UserPreference'] = $GLOBALS['dictionary']['UserPreference'];
