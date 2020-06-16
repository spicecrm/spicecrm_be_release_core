<?php

class googleAPIRestHandler
{

    public function search($term, $locationbias = 'ipbias')
    {
        global $sugar_config;

        $results = array(
            'status' => 'NOK'
        );

        $ch = curl_init();
        // https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=sol4 it&inputtype=textquery&fields=photos,formatted_address,name,place_id&key=AIzaSyCmw4Z9h4lf9eUGVyjKPyr9yr1s8WeXlPM
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key={$sugar_config['googleapi']['mapskey']}&locationbias=".trim($locationbias)."&inputtype=textquery&fields=photos,formatted_address,name,place_id&input=" . urlencode($term);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Timeout in seconds
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);

        if ($response) {
            $results = json_decode($response);
        }

        return $results;
    }

    public function autocomplete($term)
    {
        global $sugar_config;

        $results = array(
            'status' => 'NOK'
        );

        $ch = curl_init();
        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?key=" . $sugar_config['googleapi']['mapskey'] . "&types=geocode&input=" . urlencode($term);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Timeout in seconds
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);

        if ($response) {
            $results = json_decode($response);
        }

        return $results;
    }

    public function getplacedetails($placeid)
    {
        global $sugar_config, $db;

        $results = array(
            'status' => 'NOK'
        );

        $ch = curl_init();
        $url = "https://maps.googleapis.com/maps/api/place/details/json?language=en&key=" . $sugar_config['googleapi']['mapskey'] . "&placeid=" . $placeid;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);

        if ($response) {
            $responseObject = json_decode($response);
            $addrArray = array();
            foreach ($responseObject->result->address_components as $resultItem) {
                foreach ($resultItem->types as $resultType) {
                    $addrArray[$resultType] = ['long' => $resultItem->long_name,'short' => $resultItem->short_name];
                }
            }

            // build state
            $state = $db->fetchByAssoc($db->query("SELECT sc FROM syscountrystates WHERE cc='{$addrArray['country']['short']}' AND google_aa like '%{$addrArray['administrative_area_level_1']['short']}%'"));

            $results['status'] = 'OK';
            $results['address'] = array(
                'street' => $addrArray['route']['long'] . ' ' . $addrArray['street_number']['long'],
                'street_name' => $addrArray['route']['long'],
                'street_number' => $addrArray['street_number']['long'],
                'city' => $addrArray['locality']['long'],
                'district' => $addrArray['sublocality_level_1']['long'],
                'state' => $state ? $state['sc'] : $addrArray['administrative_area_level_1']['short'],
                'postalcode' => $addrArray['postal_code']['short'],
                'country' => $addrArray['country']['short'],
                'location' => $responseObject->result->geometry->location
            );
            $results['formatted_address'] = $responseObject->result->formatted_address;
            $results['formatted_phone_number'] = $responseObject->result->formatted_phone_number;
            $results['international_phone_number'] = $responseObject->result->international_phone_number;
            $results['website'] = $responseObject->result->website;
            $results['name'] = $responseObject->result->name;
        }

        return $results;
    }



}
