<?php
$app->post('/search', [new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler(), 'search']);
$app->post('/search/export', [new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler(), 'export']);