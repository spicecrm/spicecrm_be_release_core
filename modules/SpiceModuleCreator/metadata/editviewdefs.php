<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 09.05.2018
 * Time: 19:24
 */

$viewdefs['SpiceModuleCreator']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '1',
        'widths' => array(
            array('label' => '10', 'field' => '30')
        ),
        'includes'=> array(
            array('file'=>'modules/SpiceModuleCreator/javascript/SpiceModuleCreator.js'),
        ),
        'form' => array(
            'buttons' => array('SAVE')
        )
    ),
    'panels' => array(
        'default' => array(
            array(
                array(
                    'customLabel' => '<h2>Create a new module</h2>',
                    'customCode' => 'Use this form to create a new module and its basic files:<br>
                    Module directory, [ModuleClass].php, moduledefs.php and vardefs.php (structure sind spicecrm 20180100)<br>
                    <ol>
                        <li>Enter module path</li>
                        <li>Enter module name</li>
                        <li>Correct table name, bean name if necessary</li>
                        <li>Click "save"</li>
                    </ol>'
                ),
            ),
            array(
                array('name' => 'modulepath',
                    'customLabel' => 'Module path <span class="required">*</span>',
                    'customCode' => '<input name="modulepath" value="custom/modules/"> example custom/modules/'
                ),
            ),
            array(
                array('name' => 'modulename',
                    'customLabel' => 'Module name <span class="required">*</span>',
                    'customCode' => '<input name="modulename" id="modulename" value=""> example Games'
                ),
            ),
            array(
                array('name' => 'tablename',
                    'customLabel' => 'Table name <span class="required">*</span>',
                    'customCode' => '<input name="tablename" id="tablename" value=""> example games'),
            ),
            array(
                array('name' => 'beanname',
                    'customLabel' => 'Bean name <span class="required">*</span>',
                    'customCode' => '<input name="beanname" id="beanname" value=""> example Game'),
            ),
//            array(
//                array('name' => 'creatoraction',
//                    'customLabel' => 'action to perform',
//                    'customCode' => '<select name="creatoraction" ><option value="create">create</option><option value="overwrite">overwrite</option><option value="delete">delete</option> </select>'),
//            ),
        ),
    ),
);