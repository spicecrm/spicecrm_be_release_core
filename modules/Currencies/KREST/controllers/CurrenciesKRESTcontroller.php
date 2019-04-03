<?php

namespace SpiceCRM\modules\Currencies\KREST\controllers;

class CurrenciesKRESTcontroller{

    function getCurrencies(){
        global $db;
        $currency = \BeanFactory::getBean('Currencies');
        $retArray = [array(
            'id' => '-99',
            'name' => $currency->getDefaultCurrencyName(),
            'iso' =>  $currency->getDefaultISO4217(),
            'symbol' => $currency->getDefaultCurrencySymbol(),
            'conversion_rate' => 1
        )];
        $dbCurrencies = $db->query("SELECT * FROM currencies");
        while($dbCurrency = $db->fetchByAssoc($dbCurrencies)){
            $retArray[] = $dbCurrency;
        }
        return $retArray;
    }
}