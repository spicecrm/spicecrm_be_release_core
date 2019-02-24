<?php
require_once 'modules/SystemUI/SpiceUIConfLoader.php';
require_once 'modules/SystemLanguages/SpiceLanguageLoader.php';

class PackageController {

    var $confloader;
    var $langloader;

    function __construct()
    {
        global $current_user;
        // this does not work
        /*
        if(!$current_user->is_admin) {
            throw new KREST\NotFoundException("only admin access");
        }
        */

        $this->confloader = new SpiceUIConfLoader();
        $this->langloader = new SpiceLanguageLoader();
    }

    function getRepoUrl($repoid){
        global $db;
        $repourl = '';
        if($repoid){
            $repository = $db->fetchByAssoc($db->query("SELECT * FROM sysuipackagerepositories WHERE id = '{$repoid}'"));
            $repourl = $repository['url'];
        }
        if(empty($repourl)) $repourl = "https://packages.spicecrm.io";
        return $repourl;
    }

    private function checkAdmin(){
        global $current_user;
        if(!$current_user->is_admin) {
            throw new KREST\ForbiddenException();
        }
    }

    function getRepositories($req, $res, $args)
    {
        $this->checkAdmin();

        global $db;
        $repositories = [];
        $repositorieObjects = $db->query("SELECT * FROM sysuipackagerepositories");
        while($repository = $db->fetchByAssoc($repositorieObjects)){
            $repositories[] = $repository;
        };
        return $res->write(json_encode($repositories));
    }

    function getPackages($req, $res, $args)
    {
        global $db;

        $this->checkAdmin();

        $repourl = $this->getRepoUrl($args['repository']);
        $getJSONcontent = file_get_contents("{$repourl}/config");

        $content = json_decode($getJSONcontent);
        if ($this->confloader->release === true) {
            $content->versions = array();
            $content->versions[0]->version = $GLOBALS['sugar_version'];
        }
        $content->loaded = $this->confloader->getCurrentConf();
        $content->opencrs = $this->confloader->loader->hasOpenChangeRequest();

        return $res->write(json_encode($content));
    }

    function loadPackage($req, $res, $args)
    {
        $this->checkAdmin();
        $confloader = new SpiceUIConfLoader($this->getRepoUrl($args['repository']));
        return $res->write(json_encode(['response' => $confloader->loadPackage($args['package'], '*')]));
    }

    function deletePackage($req, $res, $args)
    {
        $this->checkAdmin();
        return $res->write(json_encode(['response' => $this->confloader->deletePackage($args['package'])]));
    }

    function loadLanguage($req, $res, $args)
    {
        $this->checkAdmin();
        $langloader = new SpiceLanguageLoader($this->getRepoUrl($args['repository']));
        return $res->write(json_encode($langloader->loadLanguage($args['language'])));
    }

    function deleteLanguage($req, $res, $args)
    {
        $this->checkAdmin();
        return $res->write(json_encode(['success' => $this->langloader->deleteLanguage($args['language'])]));
    }


}

