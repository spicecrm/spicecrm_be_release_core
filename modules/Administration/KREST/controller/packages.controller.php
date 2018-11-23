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

    private function checkAdmin(){
        global $current_user;
        if(!$current_user->is_admin) {
            throw new KREST\ForbiddenException();
        }
    }

    function getPackages($req, $res, $args)
    {
        $this->checkAdmin();

        $getJSONcontent = file_get_contents('https://packages.spicecrm.io/referenceconfig');

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
        return $res->write(json_encode(['response' => $this->confloader->loadPackage($args['package'])]));
    }

    function deletePackage($req, $res, $args)
    {
        $this->checkAdmin();
        return $res->write(json_encode(['response' => $this->confloader->deletePackage($args['package'])]));
    }

    function loadLanguage($req, $res, $args)
    {
        $this->checkAdmin();
        return $res->write(json_encode($this->langloader->loadLanguage($args['language'])));
    }

    function deleteLanguage($req, $res, $args)
    {
        $this->checkAdmin();
        return $res->write(json_encode(['success' => $this->langloader->deleteLanguage($args['language'])]));
    }


}

