<?php

namespace SpiceCRM\modules\EmailAddresses;

class EmailAddressRestHandler
{
    public static function searchBeans($params) {
        $results = [];

        if (isset($params['addresses'])) {
            foreach ($params['addresses'] as $address) {
                $results[$address] = $address;
                $emailAddress = new \EmailAddress();
                $results[$address] = $emailAddress->search($address);
            }
        }

        return $results;
    }
}