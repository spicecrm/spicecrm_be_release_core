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
global $dictionary;
$dictionary['opportunities_contacts'] = [
    'table' => 'opportunities_contacts',
    'fields' => [
        [
            'name' => 'id',
            'type' => 'varchar',
            'len' => '36'
        ],
        [
            'name' => 'contact_id',
            'type' => 'varchar',
            'len' => '36'
        ],
        [
            'name' => 'opportunity_id',
            'type' => 'varchar',
            'len' => '36'
        ],
        [
            'name' => 'contact_role',
            'type' => 'varchar',
            'len' => '50'
        ],
        [
            'name' => 'propensity_to_buy',
            'type' => 'enum',
            'options' => 'opportunity_relationship_buying_center_dom',
            'len' => '2'
        ],
        [
            'name' => 'level_of_support',
            'type' => 'enum',
            'options' => 'opportunity_relationship_buying_center_dom',
            'len' => '2'
        ],
        [
            'name' => 'level_of_influence',
            'type' => 'enum',
            'options' => 'opportunity_relationship_buying_center_dom',
            'len' => '2'
        ],
        [
            'name' => 'date_modified',
            'type' => 'datetime'
        ],
        [
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => false
        ]
    ],
    'indices' => [
        [
            'name' => 'opportunities_contactspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
        [
            'name' => 'idx_con_opp_con',
            'type' => 'index',
            'fields' => ['contact_id']
        ],
        [
            'name' => 'idx_con_opp_opp',
            'type' => 'index',
            'fields' => ['opportunity_id']
        ],
        [
            'name' => 'idx_opportunities_contacts',
            'type' => 'alternate_key',
            'fields' => ['opportunity_id', 'contact_id']
        ]
    ],
    'relationships' => [
        'opportunities_contacts' => [
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'opportunities_contacts',
            'join_key_lhs' => 'opportunity_id',
            'join_key_rhs' => 'contact_id'
        ]
    ]
];
