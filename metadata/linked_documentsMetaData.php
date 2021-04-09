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
$dictionary['linked_documents'] = ['table' => 'linked_documents'
   , 'fields' => [
        ['name' =>'id', 'type' =>'varchar', 'len'=>'36']
      , ['name' =>'parent_id', 'type' =>'varchar', 'len'=>'36']
      , ['name' =>'parent_type', 'type' =>'varchar', 'len'=>'25']
      , ['name' =>'document_id', 'type' =>'varchar', 'len'=>'36']
      , ['name' =>'document_revision_id', 'type' =>'varchar', 'len'=>'36']
      , ['name' =>'date_modified','type' => 'datetime']
      , ['name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>false]
    ]
   , 'indices' => [
        ['name' =>'linked_documentspk', 'type' =>'primary', 'fields'=> ['id']],
        ['name'			=> 'idx_parent_document',
				'type'			=> 'alternate_key', 
				'fields'		=> ['parent_type','parent_id','document_id'],
        ],
    ]
   , 'relationships' => [
			'contracts_documents' => ['lhs_module'=> 'Contracts', 'lhs_table'=> 'contracts', 'lhs_key' => 'id',
				   'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
				   'relationship_type'=>'many-to-many',
				   'join_table'=> 'linked_documents', 'join_key_lhs'=>'parent_id', 'join_key_rhs'=>'document_id', 'relationship_role_column'=>'parent_type',
				   'relationship_role_column_value'=>'Contracts'],
			'leads_documents' => ['lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
				   'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
				   'relationship_type'=>'many-to-many',
				   'join_table'=> 'linked_documents', 'join_key_lhs'=>'parent_id', 'join_key_rhs'=>'document_id', 'relationship_role_column'=>'parent_type',
				   'relationship_role_column_value'=>'Leads'],
			'contracttype_documents' => ['lhs_module'=> 'ContractTypes', 'lhs_table'=> 'contract_types', 'lhs_key' => 'id',
				   'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
				   'relationship_type'=>'many-to-many',
				   'join_table'=> 'linked_documents', 'join_key_lhs'=>'parent_id', 'join_key_rhs'=>'document_id', 'relationship_role_column'=>'parent_type',
				   'relationship_role_column_value'=>'ContracTemplates'],
    ],
];
?>
