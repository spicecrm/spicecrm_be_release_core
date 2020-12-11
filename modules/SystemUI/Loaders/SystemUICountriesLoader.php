<?php

namespace SpiceCRM\modules\SystemUI\Loaders;

use SpiceCRM\includes\ErrorHandlers\ForbiddenException;

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

        $states = $db->query("SELECT s.cc, s.sc, s.iso3166, s.google_aa, s.label FROM syscountrystates s, syscountries c WHERE s.cc = c.cc");
        while($state = $db->fetchByAssoc($states)){
            $retArray['states'][] = $state;
        }

        return $retArray;
    }

    /**
     * get data for only 1 country
     * @param $iso2
     * @return array[]
     */
    static function getCountryByIso2($iso2)
    {
        global $db;
        $retArray = [
            'countries' => [],
            'states' => []
        ];

        $countries = $db->query("SELECT cc, e164, label, addressformat FROM syscountries WHERE cc='{$iso2}'");
        while($country = $db->fetchByAssoc($countries)){
            $retArray['country'] = $country;
        }

        $states = $db->query("SELECT s.cc, s.sc, s.iso3166, s.google_aa, s.label FROM syscountrystates s, syscountries c WHERE s.cc = c.cc AND c.cc='{$iso2}'");
        while($state = $db->fetchByAssoc($states)){
            $retArray['states'][] = $state;
        }

        return $retArray;
    }
}
