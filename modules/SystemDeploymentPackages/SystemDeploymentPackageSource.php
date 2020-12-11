<?php
namespace SpiceCRM\modules\SystemDeploymentPackages;

class SystemDeploymentPackageSource
{
//    public static $public_source = 'https://packages.spicecrm.io/';
    public static $public_source = 'https://spicecrmreference.spicecrm.io/proxy/000/'; // for testing or dev


    /**
     * used by SpiceUILoader & SpiceInstaller
     * @return string
     */
    public static function getPublicSource(){
        $add = '';
        if(substr(self::$public_source, -1) != '/'){
            $add = '/';
        }
        return self::$public_source.$add;
    }


}
