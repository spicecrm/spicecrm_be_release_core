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
$dictionary['CampaignTracker'] = ['table' => 'campaign_trkrs',
	'comment' => 'Maintains the Tracker URLs used in campaign emails',
          
'fields' => [
    'id' => [
        'name' => 'id',
        'vname' => 'LBL_ID',
        'type' => 'id',
        'required'=>true,
        'reportable'=>false,
        'comment' => 'Unique identifier'
    ],
   'tracker_name' => [
        'name' => 'tracker_name',
        'vname' => 'LBL_TRACKER_NAME',
        'type' => 'varchar',
        'len' => '30',
        'comment' => 'The name of the campaign tracker'
   ],
  	'tracker_url' => [
        'name' => 'tracker_url',
        'vname' => 'LBL_TRACKER_URL',
        'type' => 'varchar',
        'len' => '255',
        'default' => 'http://',
        'comment' => 'The URL that represents the landing page when the tracker URL in the campaign email is clicked'
    ],
    'tracker_key' => [
        'name' => 'tracker_key',
        'vname' => 'LBL_TRACKER_KEY',
        'type' => 'int',
        'len' => '11',
        'auto_increment' => true,
        'required'=>true,
        'studio' => ['editview' => false],
        'comment' => 'Internal key to uniquely identifier the tracker URL'
    ],
  'campaign_id'=> [
    	'name'=>'campaign_id',
    	'vname'=>'LBL_CAMPAIGN_ID',
    	'type'=>'id',
    	'required'=>false,
    	'reportable'=>false,
    	'comment' => 'The ID of the campaign'
  ],
    'date_entered' => [
    	'name' => 'date_entered',
        'vname' => 'LBL_DATE_ENTERED',
        'type' => 'datetime',
		'required' => true,
		'comment' => 'Date record created'
    ],
  	'date_modified' => [
    	'name' => 'date_modified',
    	'vname' => 'LBL_DATE_MODIFIED',
    	'type' => 'datetime',
    	'required' => true,
		'comment' => 'Date record last modified'
    ],
    'modified_user_id' => [
    	'name' => 'modified_user_id',
    	'vname' => 'LBL_MODIFIED_USER_ID',
    	'dbType' => 'id',
    	'type'=>'id',
		'comment' => 'User who last modified record'
    ],
  	'created_by' => [
    	'name' => 'created_by',
    	'vname' => 'LBL_CREATED_BY',
    	'type' => 'assigned_user_name',
    	'table' => 'users',
    	'isnull' => 'false',
    	'dbType' => 'id',
		'comment' => 'User ID who created record'
    ],
  	'is_optout' => [
    	'name' => 'is_optout',
    	'vname' => 'LBL_OPTOUT',
    	'type' => 'bool',
    	'required' => true,
    	'default' => '0',
    	'reportable'=>false,
    	'comment' => 'Indicator whether tracker URL represents an opt-out link'
    ],
  	'deleted' => [
    	'name' => 'deleted',
    	'vname' => 'LBL_DELETED',
    	'type' => 'bool',
    	'required' => false,
    	'default' => '0',
    	'reportable'=>false,
    	'comment' => 'Record deletion indicator'
    ],
  	'campaign' => [
  		'name' => 'campaign',
    	'type' => 'link',
    	'relationship' => 'campaign_campaigntrakers',
    	'source'=>'non-db',
		'vname'=>'LBL_CAMPAIGN',
    ],
],

'relationships'=> [

  'campaign_campaigntrakers' => [
		'lhs_module'=> 'Campaigns', 
		'lhs_table'=> 'campaigns', 
		'lhs_key' => 'id',
   		'rhs_module'=> 'CampaignTrackers', 
		'rhs_table'=> 'campaign_trkrs', 
		'rhs_key' => 'campaign_id',
   		'relationship_type'=>'one-to-many'
  ]
]
,'indices' => [
      ['name' =>'campaign_trackepk', 'type' =>'primary', 'fields'=> ['id']],
      ['name' => 'campaign_tracker_key_idx', 'type'=>'index', 'fields'=> ['tracker_key']],
    ]
];
?>
