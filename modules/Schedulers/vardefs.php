<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

$dictionary['Scheduler'] = array('table' => 'schedulers',
	'fields' => array (
        //BEGIN CR1000162: removed default vardefs set manually.
        //make use of VarDefManager at the bottom of the file
        //END
		'job' => array (
			'name' => 'job',
			'vname' => 'LBL_JOB',
			'type' => 'varchar',
			'len' => '255',
			'required' => true,
			'reportable' => false,
		),
		'job_url' => array (
			'name' => 'job_url',
			'vname' => 'LBL_JOB_URL',
			'type' => 'varchar',
			'len' => '255',
			'required' => false,
			'reportable' => false,
			'source' => 'non-db',
			'dependency' => 'equal($job_function, "url::")'
		),
		'job_function' => array (
			'name' => 'job_function',
			'vname' => 'LBL_JOB',
			'type' => 'enum',
			'function' => array('name' => array('Scheduler', 'getJobsList'), 'params' => array()),
			'len' => '255',
			'required' => false,
			'reportable' => false,
			'source' => 'non-db',
		),
		'date_time_start' => array (
			'name' => 'date_time_start',
			'vname' => 'LBL_DATE_TIME_START',
			'type' => 'datetimecombo',
			'required' => true,
			'reportable' => false
		),
		'date_time_end' => array (
			'name' => 'date_time_end',
			'vname' => 'LBL_DATE_TIME_END',
			'type' => 'datetimecombo',
			'reportable' => false,
		),
		'job_interval' => array (
			'name' => 'job_interval',
			'vname' => 'LBL_INTERVAL',
			'type' => 'varchar',
			'len' => '100',
			'required' => true,
			'reportable' => false,
		),
		'job_interval_read' => array (
			'name' => 'job_interval_read',
			'type' => 'varchar',
            'source' => 'non-db',
        ),
		'last_status' => array (
			'name' => 'last_status',
            'vname' => 'LBL_LAST_STATUS',
            'type' => 'varchar',
            'source' => 'non-db',
        ),
		'adv_interval' => array (
			'name' => 'adv_interval',
			'vname' => 'LBL_ADV_OPTIONS',
			'type' => 'bool',
			'required' => false,
			'reportable' => false,
			'source' => 'non-db',
		),

		'time_from' => array (
			'name' => 'time_from',
			'vname' => 'LBL_TIME_FROM',
			'type' => 'time',
			'required' => false,
			'reportable' => false,
		),
		'time_to' => array (
			'name' => 'time_to',
			'vname' => 'LBL_TIME_TO',
			'type' => 'time',
			'required' => false,
			'reportable' => false,
		),
		'last_run' => array (
			'name' => 'last_run',
			'vname' => 'LBL_LAST_RUN',
			'type' => 'datetime',
			'required' => false,
			'reportable' => false,
		),
		'status' => array (
			'name' => 'status',
			'vname' => 'LBL_STATUS',
			'type' => 'enum',
			'options' => 'scheduler_status_dom',
			'len' => 100,
			'required' => false,
			'reportable' => false,
			'importable' => 'required',
		),
		'catch_up' => array (
			'name' => 'catch_up',
			'vname' => 'LBL_CATCH_UP',
			'type' => 'bool',
			'len' => 1,
			'required' => false,
			'default' => '0',
			'reportable' => false,
		),
		'schedulers_times' => array (
			'name'			=> 'schedulers_times',
			'vname'			=> 'LBL_SCHEDULER_TIMES',
			'type'			=> 'link',
			'relationship'	=> 'schedulers_jobs_rel',
			'module'		=> 'SchedulersJobs',
			'bean_name'		=> 'Scheduler',
			'source'		=> 'non-db',
		),
        'last_successful_run' => array(
            'name' => 'last_successful_run',
            'type' => 'datetime',
            'source' => 'non-db',
            'vname' => 'LBL_LAST_SUCCESSFUL_RUN'
        )
	),
	'indices' => array (
		array(
		'name' =>'schedulers_idx_start',
		'type'=>'index',
		'fields' => array(
			'date_time_start',
			'deleted'
			)
		),
	),
	'relationships' => array (
		'schedulers_created_by_rel' => array (
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Schedulers',
			'rhs_table'			=> 'schedulers',
			'rhs_key'			=> 'created_by',
			'relationship_type'	=> 'one-to-one'
		),
		'schedulers_modified_user_id_rel' => array (
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Schedulers',
			'rhs_table'			=> 'schedulers',
			'rhs_key'			=> 'modified_user_id',
			'relationship_type'	=> 'one-to-many'
		),
		'schedulers_jobs_rel' => array(
			'lhs_module'					=> 'Schedulers',
			'lhs_table'						=> 'schedulers',
			'lhs_key' 						=> 'id',
			'rhs_module'					=> 'SchedulersJobs',
			'rhs_table'						=> 'job_queue',
			'rhs_key' 						=> 'scheduler_id',
			'relationship_type' 			=> 'one-to-many',
		),
	)
);

VardefManager::createVardef('Schedulers','Scheduler', array('default'));
