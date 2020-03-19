<?php

use SpiceCRM\modules\SystemUI\SpiceUIConfLoader;

class PackageController {

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
        global $db, $sugar_config;

        $this->checkAdmin();

        $confloader = new SpiceCRM\modules\SystemUI\SpiceUIConfLoader();

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
        if ($confloader->release === true) {
            $content->versions = array();
            $content->versions[0]->version = $GLOBALS['sugar_version'];
        }
        $content->loaded = $confloader->getCurrentConf();
        $content->opencrs = $confloader->loader->hasOpenChangeRequest();

        // CR1000338 disable blacklisted packages
        if(isset( $sugar_config['packageloader']['blacklist']) && !empty($sugar_config['packageloader']['blacklist'])) {
            foreach ($content->packages as $idx => $package) {
                if (in_array($package->package, $sugar_config['packageloader']['blacklist'])) {
                    $package->extensions = 'locked by admin'; // will disable package load since there is no KREST extension by that name
                }
            }
        }
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

        $confloader = new SpiceCRM\modules\SystemUI\SpiceUIConfLoader();

        if(!$confloader) $this->getLoaders();

        return $res->write(json_encode(['response' => $confloader->deletePackage($args['package'])]));
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

        $langloader = new SpiceCRM\modules\SystemLanguages\SpiceLanguageLoader();

        return $res->write(json_encode(['success' => $langloader->deleteLanguage($args['language'])]));
    }

}

