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
$dictionary['prospect_lists_prospects'] = [

	'table' => 'prospect_lists_prospects',

	'fields' => [
		[
			'name' => 'id',
			'type' => 'varchar',
			'len' => '36',
        ],
		[
			'name' => 'prospect_list_id',
			'type' => 'varchar',
			'len' => '36',
        ],
		[
			'name' => 'related_id',
			'type' => 'varchar',
			'len' => '36',
        ],
		[
			'name' => 'related_type',
			'type' => 'varchar',
			'len' => '25',  //valid values are Prospect, Contact, Lead, User
        ],
		[
			'name' => 'quantity',
			'type' => 'varchar',
			'len' => '25',
			'default' => '0'
        ],
        [
			'name' => 'date_modified',
			'type' => 'datetime'
        ],
		[
			'name' => 'deleted',
			'type' => 'bool',
			'len' => '1',
			'default' => '0'
        ],
    ],

	'indices' => [
		[
			'name' => 'prospect_lists_prospectspk',
			'type' => 'primary',
			'fields' => ['id']
        ],
		[
			'name' => 'idx_plp_pro_id',
			'type' => 'index',
			'fields' => ['prospect_list_id']
        ],
		[
			'name' => 'idx_plp_rel_id',
			'type' => 'alternate_key',
			'fields' => ['related_id',
								'related_type',
								'prospect_list_id'
            ]
        ],
    ],

 	'relationships' => [
		'prospect_list_contacts' => ['lhs_module'=> 'ProspectLists',
											'lhs_table'=> 'prospect_lists',
											'lhs_key' => 'id',
											'rhs_module'=> 'Contacts',
											'rhs_table'=> 'contacts',
											'rhs_key' => 'id',
											'relationship_type'=>'many-to-many',
											'join_table'=> 'prospect_lists_prospects',
											'join_key_lhs'=>'prospect_list_id',
											'join_key_rhs'=>'related_id',
											'relationship_role_column'=>'related_type',
											'relationship_role_column_value'=>'Contacts'
        ],

		'prospect_list_prospects' => ['lhs_module'=> 'ProspectLists',
											'lhs_table'=> 'prospect_lists',
											'lhs_key' => 'id',
											'rhs_module'=> 'Prospects',
											'rhs_table'=> 'prospects',
											'rhs_key' => 'id',
											'relationship_type'=>'many-to-many',
											'join_table'=> 'prospect_lists_prospects',
											'join_key_lhs'=>'prospect_list_id',
											'join_key_rhs'=>'related_id',
											'relationship_role_column'=>'related_type',
											'relationship_role_column_value'=>'Prospects'
        ],

		'prospect_list_leads' => ['lhs_module'=> 'ProspectLists',
										'lhs_table'=> 'prospect_lists',
										'lhs_key' => 'id',
										'rhs_module'=> 'Leads',
										'rhs_table'=> 'leads',
										'rhs_key' => 'id',
										'relationship_type'=>'many-to-many',
										'join_table'=> 'prospect_lists_prospects',
										'join_key_lhs'=>'prospect_list_id',
										'join_key_rhs'=>'related_id',
										'relationship_role_column'=>'related_type',
										'relationship_role_column_value'=>'Leads',
        ],

		'prospect_list_users' => ['lhs_module'=> 'ProspectLists',
										'lhs_table'=> 'prospect_lists',
										'lhs_key' => 'id',
										'rhs_module'=> 'Users',
										'rhs_table'=> 'users',
										'rhs_key' => 'id',
										'relationship_type'=>'many-to-many',
										'join_table'=> 'prospect_lists_prospects',
										'join_key_lhs'=>'prospect_list_id',
										'join_key_rhs'=>'related_id',
										'relationship_role_column'=>'related_type',
										'relationship_role_column_value'=>'Users',
        ],

		'prospect_list_accounts' => ['lhs_module'=> 'ProspectLists',
											'lhs_table'=> 'prospect_lists',
											'lhs_key' => 'id',
											'rhs_module'=> 'Accounts',
											'rhs_table'=> 'accounts',
											'rhs_key' => 'id',
											'relationship_type'=>'many-to-many',
											'join_table'=> 'prospect_lists_prospects',
											'join_key_lhs'=>'prospect_list_id',
											'join_key_rhs'=>'related_id',
											'relationship_role_column'=>'related_type',
											'relationship_role_column_value'=>'Accounts',
        ],
		'prospect_list_consumers' => ['lhs_module'=> 'ProspectLists',
											'lhs_table'=> 'prospect_lists',
											'lhs_key' => 'id',
											'rhs_module'=> 'Consumers',
											'rhs_table'=> 'consumers',
											'rhs_key' => 'id',
											'relationship_type'=>'many-to-many',
											'join_table'=> 'prospect_lists_prospects',
											'join_key_lhs'=>'prospect_list_id',
											'join_key_rhs'=>'related_id',
											'relationship_role_column'=>'related_type',
											'relationship_role_column_value'=>'Consumers',
        ]
    ]

]
?>
