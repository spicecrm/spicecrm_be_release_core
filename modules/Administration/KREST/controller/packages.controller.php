<?php
// require_once 'modules/SystemUI/SpiceUIConfLoader.php';
// require_once 'modules/SystemLanguages/SpiceLanguageLoader.php';

use SpiceCRM\modules\SystemUI\SpiceUIConfLoader;

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

        $this->confloader = new SpiceCRM\modules\SystemUI\SpiceUIConfLoader();
        $this->langloader = new SpiceCRM\modules\SystemLanguages\SpiceLanguageLoader();
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
        // switched to curl
        // $getJSONcontent = file_get_contents("{$repourl}/config");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_URL, $repourl .'/config');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_ENCODING, "UTF-8");
        $getJSONcontent = curl_exec($curl);

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
        $confloader = new SpiceCRM\modules\SystemUI\SpiceUIConfLoader($this->getRepoUrl($args['repository']));
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
        $langloader = new SpiceCRM\modules\SystemLanguages\SpiceLanguageLoader($this->getRepoUrl($args['repository']));
        return $res->write(json_encode($langloader->loadLanguage($args['language'])));
    }

    function deleteLanguage($req, $res, $args)
    {
        $this->checkAdmin();
        return $res->write(json_encode(['success' => $this->langloader->deleteLanguage($args['language'])]));
    }


}

