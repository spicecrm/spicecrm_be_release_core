<?php
namespace SpiceCRM\modules\Mailboxes\processors;

class AssignProcessor extends Processor {
    public function assignAddressesToBeans() {
        $this->email->addressesToArray();

        foreach ($this->email->recipient_addresses as $address) {
            global $db;

            $query = "SELECT * FROM email_addresses WHERE email_address='" . $address['email_address'] . "'";
            $q = $db->query($query);

            while ($email_address = $db->fetchByAssoc($q)) {
                $query2 = "SELECT * FROM email_addr_bean_rel WHERE email_address_id='" . $email_address['id'] . "'";
                $q2     = $db->query($query2);
                while ($bean = $db->fetchByAssoc($q2)) {
                    $this->email->assignBeanToEmail($bean["bean_id"], $bean["bean_module"]);
                }
            }
        }
    }
}