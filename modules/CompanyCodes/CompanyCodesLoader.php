<?php

namespace SpiceCRM\modules\CompanyCodes;

class CompanyCodesLoader{
    public function loadCompanyCodes(){
        global $db;

        $retArray = [];

        $companyCode = \BeanFactory::getBean('CompanyCodes');
        $companyCodes = $companyCode->get_full_list();
        foreach ($companyCodes as $companyCode){
            $retArray[] = [
                'id' => $companyCode->id,
                'name' => $companyCode->name,
                'companycode' => $companyCode->companycode
            ];
        }

        return $retArray;

    }
}
