<?php
namespace SpiceCRM\includes\SugarFields\Fields\Phone;

use VardefManager;
use SpiceCRM\includes\SpicePhoneNumberParser\SpicePhoneNumberParser;

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
                $bean->$e164Field = SpicePhoneNumberParser::convertToE164($bean->$phoneField);
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
}
