<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 16.06.2019
 * Time: 21:01
 */

namespace SpiceCRM\modules\CompanyCodes\KREST\controllers;

class CompanyCodesKRESTController
{
    static function getCompanyCodes(){
        $loader = new \SpiceCRM\modules\CompanyCodes\CompanyCodesLoader();
        $results = $loader->loadCompanyCodes();
        return $results;
    }
}
