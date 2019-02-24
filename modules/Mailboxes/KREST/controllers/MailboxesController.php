<?php
namespace SpiceCRM\modules\Mailboxes\KREST\controllers;

use SpiceCRM\modules\Mailboxes\processors\MailboxProcessor;

class MailboxesController
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }
    /**
     * fetchEmails
     *
     * Fetches emails from a particular mailbox
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function fetchEmails($req, $res, $args) {
        $id = $args['id'];

        $mailbox = \BeanFactory::getBean('Mailboxes', $id);

        if ($mailbox->active == false) {
            return $res->write(json_encode([
                'result' => 'true',
                'message' => 'Emails were not fetched. Mailbox inactive.',
            ]));
        }

        $mailbox->initTransportHandler();

        $result = $mailbox->transport_handler->fetchEmails();

        return $res->write(json_encode($result));
    }

    /**
     * getMailboxesForDashlet
     *
     * Returns and array of Mailboxes with the number of read, unread and closed emails
     * to be used in the Mailboxes Dashlet in the UI.
     *
     * @return mixed
     */
    public function getMailboxesForDashlet($req, $res, $args) {
        $mailboxes = [];

        $sql = "SELECT ";
        $sql .= "mailboxes.id, ";
        $sql .= "mailboxes.name, ";
        $sql .= "sum(if(emails.status ='unread', 1, 0)) emailsunread, ";
        $sql .= "sum(if(emails.status ='read', 1, 0)) emailsread, ";
        $sql .= "sum(if(emails.status ='closed', 1, 0)) emailsclosed ";
        $sql .= "FROM mailboxes LEFT JOIN emails ON mailboxes.id=emails.mailbox_id ";
        $sql .= "WHERE mailboxes.deleted = 0 ";
        $sql .= "AND mailboxes.inbound_comm = 1 ";
        $sql .= "AND mailboxes.active = 1 ";
        $sql .= "AND mailboxes.hidden <> 1 ";
        $sql .= "GROUP BY mailboxes.id ";
        $sql .= "ORDER BY emailsunread DESC ";

        $response = $this->db->query($sql);

        while ($row = $this->db->fetchByAssoc($response)) {
            $mailboxes[] = $row;
        }

        return $res->write(json_encode($mailboxes));
    }

    /**
     * testConnection
     *
     * Tests connection to mail servers
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function testConnection($req, $res, $args) {
        $params = $req->getQueryParams();

        if ($params['mailbox_id'] == null) {
            return $res->write(json_encode([
                'result' => false,
                'errors' => 'No mailbox selected',
            ]));
        }
        if ($params['test_email'] == null) {
            return $res->write(json_encode([
                'result' => false,
                'errors' => 'No test email selected',
            ]));
        }
        if (!filter_var($params['test_email'], FILTER_VALIDATE_EMAIL)) {
            return $res->write(json_encode([
                'result' => false,
                'errors' => $params['test_email'] . ' is not a valid email address.',
            ]));
        }
        $result = false;

        $mailbox = \BeanFactory::getBean('Mailboxes', $params['mailbox_id']);

        if ($mailbox->initTransportHandler()) {
            $result = $mailbox->transport_handler->testConnection($params['test_email']);
        }

        return $res->write(json_encode($result));
    }

    /**
     * getMailboxProcessors
     *
     * Returns a list of all Mailbox Processors
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function getMailboxProcessors($req, $res, $args) {
        return $res->write(json_encode([
            'result' => true,
            'processors' => MailboxProcessor::all(),
        ]));
    }

    /**
     * getMailboxes
     *
     * Gets all mailboxes that are allowed for outbound communication
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function getMailboxes($req, $res, $args) {
        $result = [];

        $where = 'hidden=0';
        switch ($args->scope) {
            case 'inbound':
                $where .= ' AND inbound_comm=1';
                break;
            case 'outbound':
                $where .= ' AND (outbound_comm="single" OR outbound_comm="mass")';
                break;
            case 'outboundsingle':
                $where .= ' AND outbound_comm="single"';
                break;
            case 'outboundmass':
                $where .= ' AND outbound_comm="mass"';
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
                    'value' => $mailbox->id,
                    'display' => $mailbox->name . ' <' . $mailbox->imap_pop3_username . '>',
                    'actionset' => $mailbox->actionset,
                ]
            );
        }

        return $res->write(json_encode($result));
    }

    /**
     * setDefaultMailbox
     *
     * Sets the given Mailbox as the system default Mailbox.
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function setDefaultMailbox($req, $res, $args) {
        $params = $req->getQueryParams();

        try {
            $mailbox = \BeanFactory::getBean('Mailboxes', $params['mailbox_id']);
            return $res->write(json_encode($mailbox->setAsDefault()));
        } catch (\Exception $e) {
            return $res->write(json_encode($e));
        }
    }

    /**
     * getMailboxFolders
     *
     * Returns the mailbox folders
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function getMailboxFolders($req, $res, $args) {
        $params = $req->getQueryParams();

        $mailbox = \BeanFactory::getBean('Mailboxes', $params['mailbox_id']);
        $mailbox->initTransportHandler();

        $result = $mailbox->transport_handler->getMailboxes();

        return $res->write(json_encode($result));
    }

    public function handleSendgridEvents($req, $res, $args) {
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
}
