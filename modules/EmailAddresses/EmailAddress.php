<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

/*********************************************************************************
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 *********************************************************************************/


/**
 * Stub class, exists only to allow Link class easily use the SugarEmailAddress class
 */
class EmailAddress extends SugarEmailAddress
{
    var $disable_row_level_security = true;

    function __construct()
    {
        parent::__construct();
    }

    function save($id = '', $module = '', $new_addrs = array(), $primary = '', $replyTo = '', $invalid = '', $optOut = '', $in_workflow = false)
    {
        if (func_num_args() > 1) {
            parent::save($id, $module, $new_addrs, $primary, $replyTo, $invalid, $optOut, $in_workflow);
        } else {
            SugarBean::save($id);
        }
    }

    function search($searchterm)
    {
        global $db;

        $emailAddresses = [];

        // get an FTS manager
        $ftsManager = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();

        // determine the modules
        $modules = $db->query("SELECT * FROM sysfts");
        while ($module = $db->fetchByAssoc($modules)) {
            $emailFields = [];

            $ftsParams = json_decode(html_entity_decode($module['settings']));
            if ($ftsParams->emailsearch !== true) {
                continue;
            }

            $fields = json_decode($module['ftsfields'], true);
            foreach ($fields as $field) {
                if ($field['email'] === true) {
                    $emailFields[] = $field['indexfieldname'];
                }
            }
            $moduleResults = $ftsManager->getGlobalSearchResults($module['module'], $searchterm, null, [], [], [], $emailFields);
            foreach ($moduleResults[$module['module']]['hits'] as $hit) {
                $foundemailaddress = [];
                foreach ($emailFields as $emailField) {

                    if (is_array($hit['_source'][$emailField])) {
                        foreach ($hit['_source'][$emailField] as $thisEmail) {
                            if (array_search(strtolower($thisEmail), $foundemailaddress) !== false)
                                continue;

                            $emailAddresses[] = array(
                                'module' => $hit['_source']['_module'] ?: $hit['_type'],
                                'id' => $hit['_id'],
                                'score' => $hit['_score'],
                                'summary_text' => $hit['_source']['summary_text'],
                                'email_address' => $thisEmail,
                                'email_address_id' => $this->getEmailAddressId($thisEmail)
                            );

                            // memorize the email address
                            $foundemailaddress[] = strtolower($thisEmail);
                        }

                    } else {
                        if (empty($hit['_source'][$emailField]) || array_search(strtolower($hit['_source'][$emailField]), $foundemailaddress) !== false)
                            continue;


                        $emailAddresses[] = array(
                            'module' => $hit['_source']['_module'] ?: $hit['_type'],
                            'id' => $hit['_id'],
                            'score' => $hit['_score'],
                            'summary_text' => $hit['_source']['summary_text'],
                            'email_address' => $hit['_source'][$emailField],
                            'email_address_id' => $this->getEmailAddressId($hit['_source'][$emailField])
                        );

                        // memorize the email address
                        $foundemailaddress[] = strtolower($hit['_source'][$emailField]);
                    }
                }
            }
        }

        // sort the return array
        usort($emailAddresses, function ($a, $b) {
            if ($a['score'] == $b['score']) {
                return $a['email'] > $b['email'] ? 1 : -1;
            } else {
                return $a['score'] > $b['score'] ? -1 : 1;
            }
        });

        return $emailAddresses;
    }

    public function markEmailAddressInvalid($emailAddress)
    {
        $emailAddressBean = BeanFactory::getBean('EmailAddresses');
        $emailAddressBean->retrieve_by_string_fields(array('email_address_caps' => strtoupper($emailAddress)));
        if(!empty($emailAddressBean->id)){
            // update the email address
            $this->db->query("UPDATE email_addresses SET invalid_email = 1 WHERE id = '$emailAddressBean->id'");

            // mark all related records that are primary as not primary
            $this->db->query("UPDATE email_addr_bean_rel SET primary_address = 0 WHERE email_address_id='$emailAddressBean->id'");
        }
    }

    private function getEmailAddressId($emailAddress)
    {
        $emailAddressBean = new EmailAddress();
        $emailAddressBean->retrieve_by_string_fields(array('email_address_caps' => strtoupper($emailAddress)));
        return $emailAddressBean->id;
    }

    /**
     * saveRelation
     *
     * Only saves the relation between the current email address and another bean.
     *
     * @param $beanId
     * @param $module
     */
    public function saveRelation($beanId, $module) {
        $query = "INSERT INTO `email_addr_bean_rel` 
                (`id`, `email_address_id`, `bean_id`, `bean_module`) 
                VALUES ('" . create_guid() . "', '" . $this->id . "', '" . $beanId . "', '" . $module . "')";
        $this->db->query($query);
    }
}
