<?php

namespace SpiceCRM\modules\Currencies\KREST\controllers;

class CurrenciesKRESTcontroller{

    function getCurrencies(){
        global $db;
        $currency = \BeanFactory::getBean('Currencies');
        $retArray = [array(
            'id' => '-99',
            'name' => $currency->getDefaultCurrencyName(),
            'iso4217' =>  $currency->getDefaultISO4217(),
            'symbol' => $currency->getDefaultCurrencySymbol(),
            'conversion_rate' => 1
        )];
        $dbCurrencies = $db->query("SELECT * FROM currencies");
        while($dbCurrency = $db->fetchByAssoc($dbCurrencies)){
            $retArray[] = $dbCurrency;
        }
        return $retArray;
    }

    public function addCurrency($req, $res, $args) {
        global $current_user;
        $currency = \BeanFactory::getBean('Currencies');
        $postBody = $req->getParsedBody();

        $currency->name = $postBody['name'];
        $currency->status = 'Active';
        $currency->symbol = $postBody['symbol'];
        $currency->iso4217 = $postBody['iso'];
        $currency->created_by = $current_user->id;

        if(!$currency->save()) {
            $outcome = false;
        } else {
            $outcome = true;
        }

        return $res->withJson(array('status' => $outcome));
    }


}
