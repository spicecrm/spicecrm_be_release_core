<?php
$app->get('/sysinfo', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'getSysInfo']);
$app->group('/spiceinstaller', function () use ($app) {
    $app->get('/check', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'checkSystem']);
    $app->get('/checkreference', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'checkReference']);
    $app->get('/getlanguages', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'getLanguages']);
    $app->post('/checkdb', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'checkDB']);
    $app->post('/checkfts', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'checkFTS']);
    $app->post('/install', [new \SpiceCRM\includes\SpiceInstaller\REST\controllers\SpiceInstallerKRESTController(), 'install']);
});
