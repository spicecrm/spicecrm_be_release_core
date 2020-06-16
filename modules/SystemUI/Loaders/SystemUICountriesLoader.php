<?php

namespace SpiceCRM\modules\SystemUI\Loaders;

use KREST\ForbiddenException;

class SystemUICountriesLoader
{

    static function getCountries()
    {
        global $db;
        $retArray = [
            'countries' => [],
            'states' => []
        ];

        $countries = $db->query("SELECT cc, e164, label, addressformat FROM syscountries");
        while($country = $db->fetchByAssoc($countries)){
            $retArray['countries'][] = $country;
        }

        $states = $db->query("SELECT s.cc, s.sc, s.iso3166, s.google_aa, s.label FROM syscountrystates s, syscountries c WHERE s.cc = c.cc;");
        while($state = $db->fetchByAssoc($states)){
            $retArray['states'][] = $state;
        }

        return $retArray;
    }

}
