<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

$dictionary['potentials_opportunities'] = array(
    'table' => 'potentials_opportunities',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => '36'
        ),
        'opportunity_id' => array(
            'name' => 'opportunity_id',
            'type' => 'varchar',
            'len' => '36'
        ),
        'potential_id' => array(
            'name' => 'potential_id',
            'type' => 'varchar',
            'len' => '36'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0'
        ),
        'amount' => array(
            'name' => 'amount',
            'type' => 'currency',
            'dbType' => 'double'
        ),
        'amount_usdollar' => array(
            'name' => 'amount_usdollar',
            'type' => 'currency',
            'dbType' => 'double',
        )
    ),
    'indices' => array(
        array(
            'name' => 'potentials_opportunitiesspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_potential_opportunity',
            'type' => 'alternate_key',
            'fields' => array('potential_id', 'opportunity_id')
        ),
        array(
            'name' => 'idx_oppid_del_accid',
            'type' => 'index',
            'fields' => array('potential_id', 'deleted', 'opportunity_id')
        )
    ),
    'relationships' => array(
        'potentials_opportunities' => array(
            'lhs_module' => 'Potentials',
            'lhs_table' => 'potentials',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'potentials_opportunities',
            'join_key_lhs' => 'potential_id',
            'join_key_rhs' => 'opportunity_id'
        )
    )
);

