<?php
/**
 * the rest manager extensionfor the theme definitions
 * ToDo: add congfiguration routes
 */
global $sugar_config;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$RESTManager->registerExtension('theme', '1.0', $sugar_config['theme']);
