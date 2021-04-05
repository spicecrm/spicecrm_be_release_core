<?php

/* * *******************************************************************************
* This file is part of KReporter. KReporter is an enhancement developed
* by aac services k.s.. All rights are (c) 2016 by aac services k.s.
*
* This Version of the KReporter is licensed software and may only be used in
* alignment with the License Agreement received with this Software.
* This Software is copyrighted and may not be further distributed without
* witten consent of aac services k.s.
*
* You can contact us at info@kreporter.org
******************************************************************************* */





$pluginmetadata = [
    'id' => 'kcsvexport',
    'type' => 'integration',
    'category' => 'export',
    'displayname' => 'LBL_CSV_EXPORT',
    'integration' => [
        
        'include' => 'kcsvexport.php',
        'class' => 'kcsvexport'
    ],
    'includes' => [
        'view' => 'kcsvexport.js',
        'viewItem' => 'SpiceCRM.KReporter.Viewer.integrationplugins.csvexport.menuitem'
    ]
];
