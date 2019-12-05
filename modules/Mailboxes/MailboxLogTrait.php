<?php
namespace SpiceCRM\modules\Mailboxes;

trait MailboxLogTrait
{
    public function log($level, $message) {
        if ($this->mailbox->log_level == $level) {
            $GLOBALS['log']->error($this->mailbox->name . ': ' . $message);
        }
    }
}
