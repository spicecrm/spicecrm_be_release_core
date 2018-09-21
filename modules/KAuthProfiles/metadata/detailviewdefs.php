<?php
$viewdefs['KOrgObjects']['DetailView'] = array(

     'templateMeta' => array('maxColumns' => '2',
                             'widths' => array(
                                         array('label' => '10', 'field' => '30'),
                                         array('label' => '10', 'field' => '30')
                                         ),

                             'form' =>array(
                                     'buttons' => array (
                                        'EDIT',
                                        'DUPLICATE',
                                        'DELETE',
                                     ),

                              ),

      ),

      'panels' =>array (
          'default'=>array(
                 array('name'),
          ),

      ),
);