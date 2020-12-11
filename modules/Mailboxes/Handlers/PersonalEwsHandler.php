<?php
namespace SpiceCRM\modules\Mailboxes\Handlers;

use SpiceCRM\includes\SpiceCRMExchange\ModuleHandlers\SpiceCRMExchangeEmails;
use SpiceCRM\includes\SpiceCRMExchange\SpiceCRMExchangeConnector;
use Mailbox;

class PersonalEwsHandler extends TransportHandler
{
    private $ewsEmail;

    public function __construct(Mailbox $mailbox) {
        parent::__construct($mailbox);
    }

    /**
     * returns the mailbox name
     *
     * @return string
     */
    public function getMailboxName(){
        global $current_user;
        return "Exchange ({$current_user->user_name})";
    }

    protected function initTransportHandler() {
        return true;
    }

    public function testConnection($testEmail) {
        return ['result' => true];
    }

    protected function composeEmail($email) {
        global $current_user;
        $this->ewsEmail = new SpiceCRMExchangeEmails($current_user, $email, $this->mailbox);
        return $this->ewsEmail->composeEmail($email);
    }

    protected function dispatch($message) {
        return $this->ewsEmail->dispatch($message);
    }

    public function checkConnection() {
        global $current_user;
        $connector = new SpiceCRMExchangeConnector($current_user);
        return $connector->checkConnection();
    }
}
