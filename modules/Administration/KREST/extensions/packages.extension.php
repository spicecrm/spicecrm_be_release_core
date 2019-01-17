<?php
require_once('modules/Administration/KREST/controller/packages.controller.php');

$app->group('/packages', function () {
    $this->get('', [new PackageController(), 'getPackages']);
    $this->group('/package/{package}', function () {
        $this->get('', [new PackageController(), 'loadPackage']);
        $this->put('', [new PackageController(), 'loadPackage']);
        $this->delete('', [new PackageController(), 'deletePackage']);
    });
    $this->group('/language/{language}', function () {
        $this->get('', [new PackageController(), 'loadLanguage']);
        $this->put('', [new PackageController(), 'loadLanguage']);
        $this->delete('', [new PackageController(), 'deleteLanguage']);
        $this->post('/default', [new PackageController(), 'setDefaultLanguage']);
    });
});
