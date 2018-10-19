<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class CampaignTask extends SugarBean
{
    public $module_dir = 'CampaignTasks';
    public $object_name = 'CampaignTask';
    public $table_name = 'campaigntasks';
    public $new_schema = true;

    public $additional_column_fields = Array();

    public $relationship_fields = Array();


    public function get_summary_text()
    {
        return $this->name;
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    function activate($status = 'targeted')
    {
        $delete_query = "DELETE FROM campaign_log WHERE campaign_id='" . $this->campaign_id . "' AND campaigntask_id='" . $this->id . "' AND activity_type='$status'";
        $this->db->query($delete_query);

        $current_date = $this->db->now();
        $guidSQL = $this->db->getGuidSQL();

        $insert_query = "INSERT INTO campaign_log (id,activity_date, campaign_id, campaigntask_id, target_tracker_key,list_id, target_id, target_type, activity_type, deleted";
        $insert_query .= ')';
        $insert_query .= "SELECT {$guidSQL}, $current_date, '{$this->campaign_id}' campaign_id,  plc.campaigntask_id , {$guidSQL},plp.prospect_list_id, plp.related_id, plp.related_type,'$status',0 ";
        $insert_query .= "FROM prospect_lists INNER JOIN prospect_lists_prospects plp ON plp.prospect_list_id = prospect_lists.id";
        $insert_query .= " INNER JOIN prospect_list_campaigntasks plc ON plc.prospect_list_id = prospect_lists.id";
        $insert_query .= " WHERE plc.campaigntask_id='" . $GLOBALS['db']->quote($this->id) . "'";
        $insert_query .= " AND prospect_lists.deleted=0";
        $insert_query .= " AND plc.deleted=0";
        $insert_query .= " AND plp.deleted=0";
        $insert_query .= " AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";
        $this->db->query($insert_query);

        // set to activated
        $this->activated = true;
        $this->save();

    }

    function sendTestEmail()
    {
        $res = $this->db->query("SELECT plp.related_id, plp.related_type FROM prospect_list_campaigntasks plc INNER JOIN prospect_lists pl ON pl.list_type = 'test' AND plc.campaigntask_id = '{$this->id}' AND plc.prospect_list_id = pl.id INNER JOIN prospect_lists_prospects plp ON plp.prospect_list_id = pl.id WHERE plc.deleted = 0 AND pl.deleted = 0 AND plp.deleted = 0");
        while ($row = $this->db->fetchByAssoc($res)) {
            $bean = BeanFactory::getBean($row['related_type'], $row['related_id']);
            if ($bean->hasEmails()) {
                $this->sendEmail($bean, false, true);
            }
        }

        return true;
    }

    function sendQueuedEmails(){
        $queuedEmails = $this->db->query("SELECT id, target_type, target_id, campaigntask_id FROM campaign_log WHERE deleted = 0 AND activity_type = 'queued' AND campaigntask_id <> '' ORDER by activity_date DESC");
        while($queuedEmail = $this->db->fetchByAssoc($queuedEmails)){
            /// load the campaign task if we have a new one
            if($queuedEmail['campaigntask_id'] != $this->id){
                $this->retrieve($queuedEmail['campaigntask_id']);
            };

            // load the bean and send the email
            $seed = BeanFactory::getBean($queuedEmail['target_type'], $queuedEmail['target_id']);
            if($seed){
                $email = $this->sendEmail($seed, true);
                if($email == false){
                    $campaignLog = BeanFactory::getBean('CampaignLog', $queuedEmail['id']);
                    $campaignLog->activity_type = 'noemail';
                    $campaignLog->save();
                } else {
                    $campaignLog = BeanFactory::getBean('CampaignLog', $queuedEmail['id']);
                    $campaignLog->activity_type = 'sent';
                    $campaignLog->related_id = $email->id;
                    $campaignLog->related_type = 'Emails';
                    $campaignLog->save();
                }
            } else {
                $campaignLog = BeanFactory::getBean('CampaignLog', $queuedEmail['id']);
                $campaignLog->activity_type = 'error';
                $campaignLog->save();
            }
        }
        return true;
    }

    function sendEmail($seed, $saveEmail = false, $test = false)
    {
        $tpl = BeanFactory::getBean('EmailTemplates', $this->email_template_id);
        $parsedTpl = $tpl->parse($seed);
        $email = BeanFactory::getBean('Emails');
        $email->mailbox_id = $this->mailbox_id;
        $email->name = $parsedTpl['subject'];

        if($test)
            $email->name = 'TEST: ' . $email->name;

        $email->body = $parsedTpl['body_html'];
        $primnaryAddress = $seed->emailAddress->getPrimaryAddress($seed);
        if(!$primnaryAddress)
            return false;

        $email->addEmailAddress('to', $seed->emailAddress->getPrimaryAddress($seed));

        // add the from address
        $mailbox = BeanFactory::getBean('Mailboxes', $this->mailbox_id);
        $email->addEmailAddress('from', $mailbox->imap_pop3_username);
        // $email->from_addr = $mailbox->imap_pop3_username;

        $email->sendEmail();

        if($saveEmail){
            $email->parent_type = $seed->module_dir;
            $email->parent_ide = $seed->id;
            $email->save();
        }

        return $email;
    }


}