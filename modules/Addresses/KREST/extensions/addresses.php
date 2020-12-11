<?php
require_once(__DIR__ . '/../../../../vendor/autoload.php');

global $sugar_config;
use SpiceCRM\includes\RESTManager;
RESTManager::getInstance()->registerExtension('address_format', '1.0', $sugar_config['address']['address_format']);
