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

/*********************************************************************************

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 *********************************************************************************/

/**
 * Relationship table linking emails with 1 or more SugarBeans
 */
$dictionary['email_cache'] = [
	'table' => 'email_cache',
	'fields' => [
		'ie_id' => [
			'name'		=> 'ie_id',
			'type'		=> 'id',
        ],
		'mbox' => [
			'name'		=> 'mbox',
			'type'		=> 'varchar',
			'len'		=> 60,
			'required'	=> true,
        ],
		'subject' => [
			'name'		=> 'subject',
			'type'		=> 'varchar',
			'len'		=> 255,
			'required'	=> false,
        ],
		'fromaddr' => [
			'name'		=> 'fromaddr',
			'type'		=> 'varchar',
			'len'		=> 100,
			'required'	=> false,
        ],
		'toaddr' => [
			'name'		=> 'toaddr',
			'type'		=> 'varchar',
			'len'		=> 255,
			'required'	=> false,
        ],
		'senddate' => [
			'name'		=> 'senddate',
			'type'		=> 'datetime',
			'required'	=> false,
        ],
		'message_id' => [
			'name'		=> 'message_id',
			'type'		=> 'varchar',
			'len'		=> 255,
			'required'	=> false,
        ],
		'mailsize' => [
			'name'		=> 'mailsize',
			'type'		=> 'uint',
			'len'		=> 16,
			'required'	=> true,
        ],
		'imap_uid' => [
			'name'		=> 'imap_uid',
			'type'		=> 'uint',
			'len'		=> 32,
			'required'	=> true,
        ],
		'msgno' => [
			'name'		=> 'msgno',
			'type'		=> 'uint',
			'len'		=> 32,
			'required'	=> false,
        ],
		'recent' => [
			'name'		=> 'recent',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
        ],
		'flagged' => [
			'name'		=> 'flagged',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
        ],
		'answered' => [
			'name'		=> 'answered',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
        ],
		'deleted' => [
			'name'		=> 'deleted',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> false,
        ],
		'seen' => [
			'name'		=> 'seen',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
        ],
		'draft' => [
			'name'		=> 'draft',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
        ],
    ],
	'indices' => [
		[
			'name'			=> 'idx_ie_id',
			'type'			=> 'index',
			'fields'		=> [
				'ie_id',
            ],
        ],
		[
			'name'			=> 'idx_mail_date',
			'type'			=> 'index',
			'fields'		=> [
				'ie_id',
				'mbox',
				'senddate',
            ]
        ],
		[
			'name'			=> 'idx_mail_from',
			'type'			=> 'index',
			'fields'		=> [
				'ie_id',
				'mbox',
				'fromaddr',
            ]
        ],
		[
			'name'			=> 'idx_mail_subj',
			'type'			=> 'index',
			'fields'		=> [
				'subject',
            ]
        ],
		[
			'name'			=> 'idx_mail_to',
			'type'			=> 'index',
			'fields'		=> [
				'toaddr',
            ]
        ],

    ],
];
