<?php
namespace SpiceCRM\modules\Mailboxes\KREST\controllers;

use SpiceCRM\modules\Mailboxes\processors\MailboxProcessor;

class MailboxesController
{
    private $db;

    const TYPE_SMS   = 'sms';
    const TYPE_EMAIL = 'email';

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

        $type = $args['type'];

        $mboxes = \BeanFactory::getBean('Mailboxes');

        foreach ($mboxes->get_full_list("", "inbound_comm = 1 AND active = 1
        AND hidden <> 1") as $mbox) {
            if ($type != '' && $mbox->getType() != $type) {
                continue;
            }

            $mailboxes[] = [
                'id'           => $mbox->id,
                'name'         => $mbox->name,
                'emailsunread' => $mbox->getUnreadEmailsCount(),
                'emailsread'   => $mbox->getReadEmailsCount(),
                'emailsclosed' => $mbox->getClosedEmailsCount(),
            ];
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
//        if (!filter_var($params['test_email'], FILTER_VALIDATE_EMAIL)) {
//            return $res->write(json_encode([
//                'result' => false,
//                'errors' => $params['test_email'] . ' is not a valid email address.',
//            ]));
//        }
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
        $params = $req->getParams();

        $where = 'hidden=0';
        switch ($params['scope']) {
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
            case 'inbound_sms':
                $where .= ' AND (outbound_comm="single_sms" OR outbound_comm="mass_sms")';
                break;
            case 'outboundsingle_sms':
                $where .= ' AND outbound_comm="single_sms"';
                break;
            case 'outboundmass_sms':
                $where .= ' AND outbound_comm="mass_sms"';
                break;
        }

        $mailboxes = \BeanFactory::getBean('Mailboxes')
            ->get_full_list(
                'mailboxes.name',
                $where
            );

        foreach ($mailboxes as $mailbox) {
            $type = self::TYPE_EMAIL;
            if ($mailbox->outbund_comm == 'mass_sms' || $mailbox->outbound_comm == 'single_sms') {
                $type = self::TYPE_SMS;
            }

            array_push($result, [
                    'value'     => $mailbox->id,
                    'display'   => $mailbox->name,
                    'actionset' => $mailbox->actionset,
                    'type'      => $type,
            ]);
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
