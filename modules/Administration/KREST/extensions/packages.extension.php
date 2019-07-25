<?php
require_once('modules/Administration/KREST/controllers/packages.controller.php');

$app->group('/packages', function () {
    $this->get('/repositories', [new PackageController(), 'getRepositories']);
    $this->get('[/{repository}]', [new PackageController(), 'getPackages']);
    $this->group('/package/{package}', function () {
        $this->get('[/{repository}]', [new PackageController(), 'loadPackage']);
        $this->put('[/{repository}]', [new PackageController(), 'loadPackage']);
        $this->delete('', [new PackageController(), 'deletePackage']);
    });
    $this->group('/language/{language}', function () {
        $this->get('[/{repository}]', [new PackageController(), 'loadLanguage']);
        $this->put('[/{repository}]', [new PackageController(), 'loadLanguage']);
        $this->delete('', [new PackageController(), 'deleteLanguage']);
        $this->post('/default', [new PackageController(), 'setDefaultLanguage']);
    });
});
