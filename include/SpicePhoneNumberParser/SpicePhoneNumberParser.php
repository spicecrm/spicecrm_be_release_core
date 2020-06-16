<?php
namespace SpiceCRM\includes\SpicePhoneNumberParser;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class SpicePhoneNumberParser
{
    /**
     * convertToE164
     *
     * Converts the phone number string given by the user into the E164 standard.
     * If it's not possible it just returns the user input.
     *
     * @param $phoneNumberString
     * @return string
     */
    public static function convertToE164($phoneNumberString) {
        if ($phoneNumberString == '') {
            return '';
        }

        $phoneNumberString = preg_replace('/\D/',"",$phoneNumberString);

        global $sugar_config;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            // todo recognize the country code from the bean address data if possible
            $conversion = $phoneUtil->parse($phoneNumberString, $sugar_config['telephony']['default_country']);
            if ($phoneUtil->isValidNumber($conversion)) {
                return $phoneUtil->format($conversion, PhoneNumberFormat::E164);
            } else {
                return $phoneNumberString;
            }
        } catch (NumberParseException $e) {
            return $phoneNumberString;
        }
    }

    /**
     * convertToInternational
     *
     * Converts the phone number string given by the user into the E164 standard.
     * If it's not possible it just returns the user input.
     *
     * @param $phoneNumberString
     * @return string
     */
    public static function convertToInternational($phoneNumberString, $default_country = null) {
        global $current_user, $sugar_config;

        if ($phoneNumberString == '') {
            return '';
        }

        // try to dtermine a deault country if none is passed in
        if(!$default_country) $default_country = $current_user->address_country;
        if(!$default_country) $default_country = $sugar_config['telephony']['default_country'];

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            // todo recognize the country code from the bean address data if possible
            $conversion = $phoneUtil->parse($phoneNumberString, $default_country);
            if ($phoneUtil->isValidNumber($conversion)) {
                return $phoneUtil->format($conversion, PhoneNumberFormat::INTERNATIONAL);
            } else {
                return $phoneNumberString;
            }
        } catch (NumberParseException $e) {
            return $phoneNumberString;
        }
    }
}
