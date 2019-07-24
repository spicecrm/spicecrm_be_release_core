<?php
namespace SpiceCRM\includes\SugarFields\Fields\Phone;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use VardefManager;

class PhoneFieldHooks
{
    /**
     * hookCreateVardefs
     *
     * For each field of the type "phone" an additional field with _e164 suffix will be created.
     *
     * @param $bean
     * @param $event
     * @param $arguments
     */
    public function hookCreateVardefs(&$bean, $event, $arguments) {
        foreach ($GLOBALS['dictionary'][$bean->object_name]['fields'] as $field) {
            if ($field['type'] == "phone") {
                $this->addField($bean->object_name, $field);
            }
        }
    }

    /**
     * hookBeforeSave
     *
     * Before saving a field of the type "phone", the string will be normalized to use the E164 standard
     * and saved in the corresponding _e164 field in the DB.
     *
     * @param $bean
     * @param $event
     * @param $arguments
     */
    public function hookBeforeSave(&$bean, $event, $arguments) {
        foreach ($GLOBALS['dictionary'][$bean->object_name]['fields'] as $field) {
            if ($field['type'] == "phone") {
                $phoneField = $field['name'];
                $e164Field  = $phoneField . '_e164';
                $bean->$e164Field = $this->convertToE164($bean->$phoneField);
            }
        }
    }

    /**
     * addField
     *
     * Adds an additional phone number fields with the _e164 suffix into the database.
     *
     * @param $beanName
     * @param $field
     */
    private function addField($beanName, $field) {
        $field['name'] .= '_e164';
        $field['type'] = 'varchar';

        $GLOBALS['dictionary'][$beanName]['fields'][$field['name']] = $field;
    }

    /**
     * convertToE164
     *
     * Converts the phone number string given by the user into the E164 standard.
     * If it's not possible it just returns the user input.
     *
     * @param $userInput
     * @return string
     */
    private function convertToE164($userInput) {
        if ($userInput == '') {
            return '';
        }

        global $sugar_config;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            // todo recognize the country code from the bean address data if possible
            $conversion = $phoneUtil->parse($userInput, $sugar_config['telephony']['default_country']);
            if ($phoneUtil->isValidNumber($conversion)) {
                return $phoneUtil->format($conversion, PhoneNumberFormat::E164);
            } else {
                return $userInput;
            }
        } catch (NumberParseException $e) {
            return $userInput;
        }
    }
}
