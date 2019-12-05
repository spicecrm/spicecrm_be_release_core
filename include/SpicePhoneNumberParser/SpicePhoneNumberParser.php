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
}
