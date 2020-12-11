<?php
use SpiceCRM\modules\Currencies\KREST\controllers\CurrenciesKRESTcontroller;
use Currency;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

require_once 'modules/Currencies/Currency.php';

$RESTManager->app->group('/currencies', function () {
    $this->get('', function () {
        global $current_user, $db;

        $currency = new Currency();

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

        echo json_encode($retArray);
    });
    $this->get('/defaultcurrency',[new CurrenciesKRESTcontroller(), 'getDefaultCurrency']);
    $this->post('/add', [new CurrenciesKRESTcontroller(), 'addCurrency']);
});
