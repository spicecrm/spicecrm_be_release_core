<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');


class EmailSchedule extends SugarBean
{
    public $module_dir = 'EmailSchedules';
    public $object_name = 'EmailSchedule';
    public $table_name = 'emailschedules';

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

    /**
     * generate and send email to seed
     * @param $seed
     * @param $id
     * @param bool $saveEmail
     * @return bool|SugarBean
     */
    public function sendEmail($seed, $id, $saveEmail = false)
    {
        $res = $this->db->query("SELECT * from emailschedules WHERE id = '$id' AND deleted = 0");
        $emailSchedule = $this->db->fetchByAssoc($res);

        $template = BeanFactory::getBean('EmailTemplates');
        $template->subject = $emailSchedule['email_subject'];
        $template->body_html = $emailSchedule['email_body'];
        $template->style = $emailSchedule['email_stylesheet_id'];

        $parsedTemplate = $template->parse($seed);
        $email = BeanFactory::getBean('Emails');

        $email->mailbox_id = $emailSchedule['mailbox_id'];
        $email->name = $parsedTemplate['subject'];
        $email->body = $parsedTemplate['body_html'];

        $primaryAddress = $seed->emailAddress->getPrimaryAddress($seed);

        if(empty($primaryAddress)) {
            return false;
        }
        $email->addEmailAddress('to', $seed->emailAddress->getPrimaryAddress($seed));
        $mailbox = BeanFactory::getBean('Mailboxes', $emailSchedule['mailbox_id']);
        $email->addEmailAddress('from', $mailbox->imap_pop3_username);

        $email->sendEmail();

        if($saveEmail){
            $email->parent_type = $seed->module_dir;
            $email->parent_id = $seed->id;
            $email->save();
        }

        return $email;
    }

    /**
     * retrieve, then send queued emails, log them as sent
     * @return bool
     */
    public function sendQueuedEmails()
    {
        $queuedEmails = $this->db->query("SELECT * from emailschedules_beans WHERE emailschedule_status = 'queued' AND deleted = 0 ORDER by date_modified DESC");
        while ($queuedEmail = $this->db->fetchByAssoc($queuedEmails)) {
            $seed = BeanFactory::getBean($queuedEmail['bean_module'], $queuedEmail['bean_id']);
            $emailscheduleID = $queuedEmail['emailschedule_id'];
            if (empty($seed)) {
                return false;
            } else {
                $email = $this->sendEmail($seed, $emailscheduleID, true);
                if($email == false) {
                    return false;
                } else {
                    $this->db->query("UPDATE emailschedules_beans SET emailschedule_status = 'sent', email_id='$email->id' WHERE emailschedule_id='$emailscheduleID'");
                    $this->db->query("UPDATE emailschedules SET email_schedule_status = 'done' WHERE id='$emailscheduleID'");
                }
            }

        }
        return true;
    }


}
