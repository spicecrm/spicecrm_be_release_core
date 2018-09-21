<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 09.05.2018
 * Time: 19:24
 */

$viewdefs['SpiceModuleCreator']['DetailView'] = array(
    'templateMeta' => array('maxColumns' => '1',
        'widths' => array(
            array('label' => '10', 'field' => '30')
        ),
        'form' => array(
            'buttons' => array()
        )
    ),
    'panels' => array(
        'default' => array(
            array(
                array('name' => 'modulename',
                    'customLabel' => 'Module name',
                    'customCode' => '<input name="modulename" value="'.$_GET['record'].'">'
                ),
            ),
        ),
    ),
);