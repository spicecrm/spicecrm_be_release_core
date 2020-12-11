<?php
use SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController;

$app->get('/isysinfo', [new SpiceInstallerKRESTController(), 'getSysInfo']);
$app->group('/spiceinstaller', function ()  use ($app) {
    $app->get('/check', [new SpiceInstallerKRESTController(), 'checkSystem']);
    $app->get('/checkreference', [new SpiceInstallerKRESTController(), 'checkReference']);
    $app->get('/getlanguages', [new SpiceInstallerKRESTController(), 'getLanguages']);
    $app->post('/checkdb', [new SpiceInstallerKRESTController(), 'checkDB']);
    $app->post('/checkfts', [new SpiceInstallerKRESTController(), 'checkFTS']);
    $app->post('/install', [new SpiceInstallerKRESTController(), 'install']);
});
