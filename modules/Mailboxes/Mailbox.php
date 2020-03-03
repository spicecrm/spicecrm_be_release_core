<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

require_once 'include/SpiceAttachments/SpiceAttachments.php';

class Mailbox extends SugarBean {

	public $module_dir = 'Mailboxes';
	public $table_name = "mailboxes";
	public $object_name = "Mailbox";

	public $transport_handler;

    const LOG_NONE  = 0;
    const LOG_ERROR = 1;
    const LOG_DEBUG = 2;

    const TRANSPORT_EWS      = 'ews';
    const TRANSPORT_IMAP     = 'imap';
    const TRANSPORT_MAILGUN  = 'mailgun';
    const TRANSPORT_SENDGRID = 'sendgrid';

    /**
     * Mailbox constructor.
     */
	public function __construct() {
        parent::__construct();
	}

	function get_summary_text()
	{
		return $this->name;
	}

	function fill_in_additional_list_fields()
	{
		parent::fill_in_additional_list_fields();
	// Fill in the assigned_user_name
	//	$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

	}

	function fill_in_additional_detail_fields()
	{
        parent::fill_in_additional_detail_fields();
	}
	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}

    /**
     * initTransportHandler
     *
     * Initializes the mailbox transport handler according to the transport setting
     *
     * @return bool
     * @throws Exception
     */
	public function initTransportHandler()
    {
        if ($this->settings != '') {
            $this->initializeSettings();
        }

        $class_name = "\\SpiceCRM\\modules\\Mailboxes\\Handlers\\" . ucfirst($this->transport) . "Handler";
        
        if (!class_exists($class_name)) {
            $class_name = "\\SpiceCRM\\custom\\modules\\Mailboxes\\Handlers\\" . ucfirst($this->transport) . "Handler";
            if (!class_exists($class_name)) {
                throw new Exception('Transport Handler '
                    . "\\SpiceCRM\\modules\\Mailboxes\\Handlers\\" . ucfirst($this->transport) . "Handler"
                    . ' or ' . $class_name . ' do not exist.');
            }
        }

        $this->transport_handler = new $class_name($this);

        return true;
    }

    /**
     * getSentFolder
     *
     * Returns the whole IMAP path to the sent email folder
     *
     * @return string
     */
    public function getSentFolder() {
	    return $this->getRef() . $this->imap_sent_dir;
    }

    /**
     * getRef
     *
     * Concatenates mailbox info into a connection string
     *
     * @return string
     */
    public function getRef()
    {
        $ref = '{' . $this->imap_pop3_host . ":" . $this->imap_pop3_port . '/' . $this->imap_pop3_protocol_type;
        if ($this->imap_pop3_encryption == 'ssl') {
            $ref .= '/ssl/novalidate-cert';
        } elseif ($this->imap_pop3_encryption == 'tls') {
            $ref .= '/tls/novalidate-cert';
        }
        $ref .= '}';

        return $ref;
    }

    /**
     * save
     *
     * Saves the Mailbox bean
     *
     * @param bool $check_notify
     * @param bool $fts_index_bean
     * @return string|void
     */
    public function save($check_notify = false, $fts_index_bean = true) {

        parent::save($check_notify, $fts_index_bean);
    }

    public function mapToRestArray($beanDataArray)
    {
        // Need to initialize it as an array, otherwise
        // PHP >=7.1 triggers an error
        // [] operator not supported by string
        $beanDataArray['mailbox_processors'] = [];

        $q = "SELECT *
				FROM mailbox_processors
				WHERE mailbox_id = '{$this->id}'";
        $r = $this->db->query($q);

        while ($a = $this->db->fetchByAssoc($r)) {
            $beanDataArray['mailbox_processors'][] = $a;
        }

        return $beanDataArray;
    }

    public function mapFromRestArray($beanDataArray)
    {
        foreach ($beanDataArray['mailbox_processors'] as $processorData) {
            $processor = new \SpiceCRM\modules\Mailboxes\processors\MailboxProcessor($processorData);
            if ($processor->deleted && $processor->id != '') {
                $processor->delete();
                continue;
            }

            $processor->mailbox_id = $this->id;

            if ($processor->validate()) {
                $processor->save();
            }
        }
    }

    /**
     * initializeSettings
     *
     * Converts the mailbox settings stored as json into object attributes
     *
     * @return void
     */
    private function initializeSettings() {
        $settings = json_decode(html_entity_decode($this->settings, ENT_QUOTES));

        foreach ($settings as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * getDefaultMailbox
     *
     * Returns the default system mailbox
     *
     * @return bool|Mailbox
     * @throws Exception
     */
    public static function getDefaultMailbox()
    {// todo errors when no default mailbox available

        if ( !empty( $GLOBALS['installing'] )) return false;

//        global $db;
//        if(is_null($db)){
//            $db = DBManagerFactory::getInstance();
//        }
//        $defaultId = null;
//        $q = "SELECT id FROM mailboxes WHERE is_default=1 AND deleted=0";
//        $r = $db->query($q);
//        while ($row = $db->fetchByAssoc($r)) {
//            $defaultId = $row['id'];
//        }

//        $defaultMailbox =  BeanFactory::getBean('Mailboxes', $defaultId);

        $defaultMailbox = BeanFactory::getBean('Mailboxes');
        $defaultMailbox = $defaultMailbox->retrieve_by_string_fields(array('is_default' => true));
        //getBean will return Mailboxe object with empty properties if $defaultId is null
        //Check on property id
        if (is_null($defaultMailbox) || empty($defaultMailbox->id)) {
            # logging temporarily turned off to prevent messing sugarcrm.log:
            # $GLOBALS['log']->fatal('No default Mailbox found');
            if ( @$GLOBALS['isREST'] ) {
                throw new Exception('No default Mailbox found');
            } else {
                return false;
            }
        } else {
            return $defaultMailbox;
        }
    }

    /**
     * setAsDefault
     *
     * Sets current Mailbox as the default system mailbox
     * Unsets the default flag on all other mailboxes
     *
     * @return boolean
     */
    public function setAsDefault()
    {
        $q1 = "UPDATE mailboxes SET is_default=0 WHERE 1";
        $this->db->query($q1);
        $q2 = "UPDATE mailboxes SET is_default=1 WHERE id='" . $this->id . "'";
        $this->db->query($q2);
        return true;
    }

    /**
     * to use instead of this bad named property imap_pop3_username...
     * for a better future...
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->imap_pop3_username;
    }

    // todo find out where those settings are needed and if they're actually useful
    public static function getSystemMailerSettings() {
        $systemMailbox   = self::getDefaultMailbox();
        $defaultSettings = [];

        if ($systemMailbox->transport == 'imap') {
            $defaultSettings = [
                'mail_sendtype'     => 'SMTP',
                'mail_smtptype'     => 'other',
                'mail_smtpserver'   => $systemMailbox->smtp_host,
                'mail_smtpport'     => $systemMailbox->smtp_port,
                'mail_smtpuser'     => $systemMailbox->imap_pop3_username,
                'mail_smtppass'     => $systemMailbox->imap_pop3_password,
                'mail_smtpauth_req' => $systemMailbox->smtp_auth,
                'mail_smtpssl'      => ($systemMailbox->smtp_encryption == 'none') ? '0' : '1',
            ];
        } else {
            // todo figure out what to with default mailgun/sendgrid mailboxes for now just return hardcoded values
            $defaultSettings = [
                'mail_sendtype'     => 'SMTP',
                'mail_smtptype'     => 'other',
                'mail_smtpserver'   => '',
                'mail_smtpport'     => '25',
                'mail_smtpuser'     => '',
                'mail_smtppass'     => '',
                'mail_smtpauth_req' => '1',
                'mail_smtpssl'      => '0',
            ];
        }

        return $defaultSettings;
    }

    /**
     * findByPhoneNumber
     *
     * Searches for a Mailbox with the given number set as phone_number_from in the JSON settings.
     * Useful only for SMS Mailboxes.
     *
     * @param $phoneNo
     * @return bool|Mailbox
     */
    public static function findByPhoneNumber($phoneNo) {
        global $db;

        $query = "SELECT id FROM mailboxes WHERE inbound_comm='1' AND (outbound_comm='single_sms' OR outbound_comm='mass_sms')";
        $q = $db->query($query);

        while($row = $db->fetchRow($q)) {
            $mailbox = BeanFactory::getBean('Mailboxes', $row['id']);
            if ($mailbox->settings != '') {
                $mailbox->initTransportHandler();
            }
            if ($mailbox->phone_number_from == $phoneNo) {
                return $mailbox;
            }
        }

        return false;
    }

    public function getUnreadEmailsCount() {
        global $db;

        $query = "SELECT COUNT(*) as cnt from " . $this->getMessagesTable() . " WHERE mailbox_id='" . $this->id . "'"
                . " AND status='unread'";
        $q = $db->query($query);
        $result = $db->fetchByAssoc($q);

        return $result['cnt'];
    }

    public function getReadEmailsCount() {
        global $db;

        $query = "SELECT COUNT(*) as cnt from " . $this->getMessagesTable() . " WHERE mailbox_id='" . $this->id . "'"
            . " AND status='read'";
        $q = $db->query($query);
        $result = $db->fetchByAssoc($q);

        return $result['cnt'];
    }

    public function getClosedEmailsCount() {
        global $db;

        $query = "SELECT COUNT(*) as cnt from " . $this->getMessagesTable() . " WHERE mailbox_id='" . $this->id . "'"
            . " AND status='closed'";
        $q = $db->query($query);
        $result = $db->fetchByAssoc($q);

        return $result['cnt'];
    }

    // todo just add a type field to the mailbox table, coz this is getting too messy
    // and won't work for exclusively inbound mailboxes
    private function getMessagesTable() {
        if ($this->outbound_comm == 'single_sms' || $this->outbound_comm == 'mass_sms') {
            return 'textmessages';
        }
        if ($this->outbound_comm == 'single' || $this->outbound_comm == 'mass') {
            return 'emails';
        }
        return '';
    }

    public function getType() {
        if ($this->outbound_comm == 'single_sms' || $this->outbound_comm == 'mass_sms') {
            return 'sms';
        }
        if ($this->outbound_comm == 'single' || $this->outbound_comm == 'mass') {
            return 'email';
        }
        return '';
    }

    /**
     * Checks if a mailbox uses EWS and if the EWS push notifications are turned on.
     *
     * @return bool
     */
    public function usesEwsNotifications() {
        if ($this->transport != self::TRANSPORT_EWS) {
            return false;
        }

        if (!isset($this->ews_push)) {
            return false;
        }

        if ($this->ews_push == false) {
            return false;
        }

        return true;
    }

    /**
     * Checks if a mailbox has non empty EWS subscription data stored in the settings.
     *
     * @return bool
     */
    public function hasEwsSubscription() {
        if (!$this->usesEwsNotifications()) {
            return false;
        }

        if (!isset($this->ews_subscriptionid) || !isset($this->ews_watermark)) {
            return false;
        }

        if ($this->ews_subscription == '' || $this->ews_watermark == '') {
            return false;
        }

        return true;
    }
}
