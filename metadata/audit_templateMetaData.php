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

/* this table should never get created, it should only be used as a template for the acutal audit tables
 * for each moudule.
 */
$dictionary['audit'] =
    ['table' => 'audit_template',
        'fields' => [
            'id'=> ['name' =>'id', 'type' =>'id', 'len'=>'36','required'=>true],
            'parent_id'=> ['name' =>'parent_id', 'type' =>'id', 'len'=>'36','required'=>true],
            'transaction_id'=> ['name' =>'transaction_id', 'type' =>'varchar', 'len'=>'36','required'=>false],
            'date_created'=> ['name' =>'date_created','type' => 'datetime'],
            'created_by'=> ['name' =>'created_by','type' => 'varchar','len' => 36],
            'field_name'=> ['name' =>'field_name','type' => 'varchar','len' => 100],
            'data_type'=> ['name' =>'data_type','type' => 'varchar','len' => 100],
            'before_value_string'=> ['name' =>'before_value_string','type' => 'varchar'],
            'after_value_string'=> ['name' =>'after_value_string','type' => 'varchar'],
            'before_value_text'=> ['name' =>'before_value_text','type' => 'text'],
            'after_value_text'=> ['name' =>'after_value_text','type' => 'text'],
        ],
        'indices' => [
            //name will be re-constructed adding idx_ and table name as the prefix like 'idx_accounts_'
            ['name' => 'pk', 'type' => 'primary', 'fields' => ['id']],
            ['name' => 'parent_id', 'type' => 'index', 'fields' => ['parent_id']],
            ['name' => 'field_name', 'type' => 'index', 'fields' => ['field_name']],
        ]
    ];
