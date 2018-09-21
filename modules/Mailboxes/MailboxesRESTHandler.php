<?php
namespace SpiceCRM\modules\Mailboxes;

use SpiceCRM\modules\Mailboxes\processors\MailboxProcessor;

require_once 'include/SpiceAttachments/SpiceAttachments.php';

/**
 * Class MailboxesRESTHandler
 * Handles the REST requests for the mailbox module
 */
class MailboxesRESTHandler
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    /**
     * testConnection
     *
     * Tests connection to mail servers
     *
     * @param array $params
     * @return array
     */
    public function testConnection(array $params)
    {
        if ($params['mailbox_id'] == null) {
            return [
                'result' => false,
                'errors' => 'No mailbox selected',
            ];
        }
        $result = false;

        $mailbox = \BeanFactory::getBean('Mailboxes', $params['mailbox_id']);

        if ($mailbox->initTransportHandler()) {
            $result = $mailbox->transport_handler->testConnection();
        }

        return $result;
    }

    /**
     * getMailboxFolders
     *
     * Returns the mailbox folders
     *
     * @param array $params
     * @return array
     */
    public function getMailboxFolders(array $params)
    {
        $mailbox = \BeanFactory::getBean('Mailboxes', $params['mailbox_id']);
        $mailbox->initTransportHandler();

        $result = $mailbox->transport_handler->getMailboxes();

        return $result;
    }

    /**
     * getMailboxProcessors
     *
     * Returns a list of all Mailbox Processors
     *
     * @return array
     */
    public function getMailboxProcessors()
    {
        return [
            'result'     => true,
            'processors' => MailboxProcessor::all(),
        ];
    }

    /**
     * fetchEmails
     *
     * Fetches emails from a particular mailbox
     *
     * @param array $params
     * @return array
     */
    public function fetchEmails($id)
    {
        $mailbox = \BeanFactory::getBean('Mailboxes', $id);
        $mailbox->initTransportHandler();

        $result = $mailbox->transport_handler->fetchEmails();

        return $result;
    }

    /**
     * getMailboxes
     *
     * Gets all mailboxes that are allowed for outbound communication
     *
     * @return array
     */
    public function getMailboxes($args)
    {
        $result = [];

        $where = '';
        switch($args['scope']){
            case 'inbound':
                $where = 'inbound_comm= 1';
                break;
            case 'outbound':
                $where = '(outbound_comm="single" OR outbound_comm="mass")';
                break;
            case 'outboundsingle':
                $where = 'outbound_comm="single"';
                break;
            case 'outboundmass':
                $where = 'outbound_comm="mass"';
                break;
        }

        $mailboxes = \BeanFactory::getBean('Mailboxes')
            ->get_full_list(
                'mailboxes.name',
                $where
            );

        foreach ($mailboxes as $mailbox) {
            array_push($result,
                [
                    'value'     => $mailbox->id,
                    'display'   => $mailbox->name . ' <' . $mailbox->imap_pop3_username . '>',
                    'actionset' => $mailbox->actionset,
                ]
            );
        }

        return $result;
    }

    public function handleSendgridEvents($params) {
        $data = file_get_contents("php://input");
        $events = json_decode($data, true);

        foreach ($events as $event) {
            try {
                $email = \Email::findByMessageId($event['smtp-id']);
                $email->status = $event['event'];
                $email->save();
            } catch (\Exception $e) {
                $GLOBALS['log']->info($e->getMessage());
            }

            /*switch ($event['event']) {
                case 'delivered':
                    break;
                case 'processed':
                    break;
                case 'dropped':
                    break;
                case 'bounce':
                    break;
                case 'deferred':
                    break;
                case 'open':
                    break;
                case 'click':
                    break;
                case 'unsubscribe':
                    break;
                case 'spamreport':
                    break;
            }*/
        }
    }

    public function setDefaultMailbox($params) {
        try {
            $mailbox = \BeanFactory::getBean('Mailboxes', $params['mailbox_id']);
            return $mailbox->setAsDefault();
        } catch (\Exception $e) {
            return $e;
        }

    }
}