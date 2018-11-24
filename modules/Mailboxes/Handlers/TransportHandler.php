<?php
namespace SpiceCRM\modules\Mailboxes\Handlers;

abstract class TransportHandler
{
    protected $mailbox;
    protected $transport_handler;
    protected $logger;
    protected $incoming_settings = [];
    protected $outgoing_settings = [];

    public function __construct(\Mailbox $mailbox)
    {
        $this->mailbox = $mailbox;

        $this->initTransportHandler();

        $this->logger = new \SugarLogger();
    }

    abstract protected function initTransportHandler();

    abstract public function testConnection($testEmail);

    public function sendMail(\Email $email)
    {
        if ($this->mailbox->active == false) {
            return [
                'result'  => 'false',
                'message' => 'Message not sent. Mailbox inactive.',
            ];
        }

        if ($this->mailbox->mailbox_header != '') {
            $email->body = html_entity_decode($this->mailbox->mailbox_header) . $email->body;
        }

        if ($this->mailbox->mailbox_footer != '') {
            $email->body .= html_entity_decode($this->mailbox->mailbox_footer);
        }

        if ($this->mailbox->stylesheet != '') {
            $email->addStylesheet($this->mailbox->stylesheet);
        }

        $message = $this->composeEmail($email);

        return $this->dispatch($message);
    }

    abstract protected function composeEmail(\Email $email);

    abstract protected function dispatch($message);

    /**
     * checkConfiguration
     *
     * Check existence of configuration settings
     *
     * @param $settings
     * @return array
     */
    protected function checkConfiguration($settings) {
        $response = [
            'result'  => true,
            'missing' => [],
        ];

        foreach ($settings as $setting) {
            if (!isset($this->mailbox->$setting) || $this->mailbox->$setting == '') {
                $response['result'] = false;
                array_push($response['missing'], $setting);
                continue;
            }
        }

        return $response;
    }
}
