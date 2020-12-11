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

$dictionary['spicefavorites'] = array(
	'table' => 'spicefavorites',
	'fields' => array(
		array(
			'name' => 'beanid',
			'type' => 'varchar',
			'len' => 36
		),
		array(
			'name' => 'user_id',
			'type' => 'varchar',
			'len' => '36'
		),
		array(
			'name' => 'bean',
			'type' => 'varchar',
			'len' => '36',
		),
		array(
			'name' => 'date_entered',
			'type' => 'datetime'
		)
	),
	'indices' => array(
 		array(	'name'			=> 'tfr_idx',
				'type'			=> 'unique',
				'fields'		=> array('beanid', 'user_id'),
		),
 		array(	'name'			=> 'tsrusr_idx',
				'type'			=> 'index',
				'fields'		=> array('user_id'),
		),
		array(	'name'			=> 'tsrusrbean_idx',
				'type'			=> 'index',
				'fields'		=> array('user_id', 'bean'),
		),
	),

);
$dictionary['spicereminders'] = array(
	'table'=> 'spicereminders',
	'fields'=> array(
		array(	'name'			=> 'user_id',
				'type'			=> 'varchar',
				'len'			=> '36'
		),
		array(	'name'			=> 'bean',
				'type'			=> 'varchar',
				'len'			=> '36',
		),
		array(	'name'			=> 'bean_id',
				'type'			=> 'varchar',
				'len'			=> '36',
		),
		array(	'name'			=> 'reminder_date',
				'type'			=> 'date'
		)
 	),
	'indices' => array(
 		array(	'name'			=> 'tsr_idx',
				'type'			=> 'unique',
				'fields'		=> array('user_id', 'bean_id'),
		)
	),
);

$dictionary['spicenotes'] = array(
		'table' => 'spicenotes',
		'fields' => array(
				array(
						'name' => 'id',
						'type' => 'varchar',
						'len' => 36
				),
				array(
						'name' => 'bean_type',
						'type' => 'varchar',
						'len' => 100
				),
				array(
						'name' => 'bean_id',
						'type' => 'varchar',
						'len' => 36
				),
				array(
						'name' => 'user_id',
						'type' => 'varchar',
						'len' => 36
				),
				array(
						'name' => 'trdate',
						'type' => 'datetime'
				),
				array(
						'name' => 'trglobal',
						'type' => 'bool'
				),
				array(
						'name' => 'text',
						'type' => 'text'
				),
				array(
						'name' => 'deleted',
						'type' => 'bool'
				),
		),
		'indices' => array(
				array(	'name'			=> 'tqn_idx',
						'type'			=> 'unique',
						'fields'		=> array('id'),
				),
				array(	'name'			=> 'tqnusr_idx',
						'type'			=> 'index',
						'fields'		=> array('user_id'),
				),
				array(	'name'			=> 'tqnusrbean_idx',
						'type'			=> 'index',
						'fields'		=> array('bean_type', 'bean_id'),
				),
				array(	'name'			=> 'tqnselection_idx',
						'type'			=> 'index',
						'fields'		=> array('bean_type', 'bean_id', 'user_id', 'deleted'),
				),
		),

);

$dictionary['spiceattachments'] = [
	'table'  => 'spiceattachments',
	'fields' => [
		[
			'name' => 'id',
			'type' => 'varchar',
			'len'  => 36,
		],
		[
			'name' => 'bean_type',
			'type' => 'varchar',
			'len'  => 100,
		],
		[
			'name' => 'bean_id',
			'type' => 'varchar',
			'len'  => 36,
		],
		[
			'name' => 'user_id',
			'type' => 'varchar',
			'len'  => 36,
		],
		[
			'name' => 'trdate',
			'type' => 'datetime',
		],
		[
			'name' => 'filename',
			'type' => 'varchar',
			'len'  => 150,
		],
		[
			'name' => 'display_name',
			'type' => 'varchar'
		],
		[
			'name' => 'filesize',
			'type' => 'ulong',
		],
		[
			'name' => 'filemd5',
			'type' => 'varchar',
            'len'  => 32,
		],
		[
			'name' => 'file_mime_type',
			'type' => 'varchar',
			'len'  => 150,
		],
		[
			'name' => 'text',
			'type' => 'text',
		],
		[
			'name' => 'category_ids',
			'type' => 'varchar',
		],
		[
			'name' => 'thumbnail',
			'type' => 'text',
		],
		[
			'name' => 'deleted',
			'type' => 'bool',
		],
        [
            'name' => 'external_id',
            'type' => 'varchar',
            'len'  => 200,
        ],
	],
	'indices' => [
		[
		    'name'	 => 'tqn_idx2',
			'type'	 => 'unique',
			'fields' => ['id'],
		],
		[
		    'name'	 => 'tatusr_idx',
			'type'	 => 'index',
			'fields' => ['user_id'],
		],
		[
		    'name'	 => 'tatusrbean_idx',
			'type'	 => 'index',
			'fields' => ['bean_type', 'bean_id', 'trdate'],
		],
		[
		    'name'	 => 'tatselection_idx',
			'type'	 => 'index',
			'fields' => ['bean_type', 'bean_id', 'deleted'],
		],
		[
		    'name'	 => 'tatmd5_idx',
			'type'	 => 'index',
			'fields' => ['filemd5', 'deleted'],
		],
	],
];

$dictionary['spiceattachments_categories'] = [
	'table'  => 'spiceattachments_categories',
	'fields' => [
		[
			'name' => 'id',
			'type' => 'id'
		],
        [
            'name' => 'name',
            'type' => 'varchar',
        ],
		[
			'name' => 'label',
			'type' => 'varchar'
		],
        [
            'name' => 'module',
            'type' => 'varchar',
            'len'  => 100,
        ],
	],
	'indices' => [
		[
		    'name'	 => 'spiceattchments_categories_idx',
			'type'	 => 'unique',
			'fields' => ['id'],
		]
	],
];
