<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->post('/search', [new SpiceFTSHandler(), 'search']);
$RESTManager->app->post('/search/phonenumber', [new SpiceFTSHandler(), 'searchPhone']);
$RESTManager->app->post('/search/export', [new SpiceFTSHandler(), 'export']);
