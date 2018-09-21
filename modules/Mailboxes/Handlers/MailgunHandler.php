<?php
namespace SpiceCRM\modules\Mailboxes\Handlers;

use Mailgun\Mailgun;

class MailgunHandler extends TransportHandler
{
    protected $outgoing_settings = [
        'api_key',
        'imap_pop3_username',
        'domain',
    ];

    protected function initTransportHandler()
    {
        $this->transport_handler = Mailgun::create($this->mailbox->api_key);
    }

    public function testConnection()
    {
        $result['result'] = false;

        try {
            $response = $this->transport_handler->domains()->verify($this->mailbox->domain);

            if ($response->getMessage() == "Domain DNS records have been updated") {
                $result['result'] = true;
            } else {
                $result['errors'] = $response->getMessage();

                $GLOBALS['log']->info($response->getMessage());
            }
        } catch (\Exception $e) {
            $result['errors'] = $e->getMessage();

            $GLOBALS['log']->info($e->getMessage());
        }

        return $result;
        /*$response = $this->sendMail(\Email::getTestEmail($this->mailbox));

        return $response;*/
    }

    protected function composeEmail(\Email $email)
    {
        $message = $this->transport_handler->messageBuilder();
        // todo add email names and multiple addresses
        $message->setFromAddress(
            $this->mailbox->imap_pop3_username,
            ['full_name' => $this->mailbox->imap_pop3_display_name]
        );
        $message->addToRecipient($email->to_addrs);
        if (!empty($email->cc_addrs)) {
            $message->addCcRecipient($email->cc_addrs);
        }
        if (!empty($email->bcc_addrs)) {
            $message->addBccRecipient($email->bcc_addrs);
        }
        $message->setSubject($email->name);
        $message->setHtmlBody($email->body);

        foreach (json_decode(\SpiceAttachments::getAttachmentsForBean('Emails', $email->id)) as $att) {
            $message->addAttachment(
                'upload://' . $att->filemd5, $att->filename
            );
        }

        return $message;
    }

    protected function dispatch($message)
    {
        try {
            $response = $this->transport_handler->sendMessage(
                $this->mailbox->domain,
                $message->getMessage(),
                $message->getFiles()
            );

            if ($response->http_response_code === 200) {
                $result = [
                    'result'     => true,
                    'message_id' => $this->extractMessageId($response),
                ];
            } else {
                $result = [
                    'result' => false,
                    'errors' => $response->http_response_body->message,
                ];
                $GLOBALS['log']->info($response->http_response_body->message);
            }
        } catch (\Exception $exception) {
            $result = [
                'result' => false,
                'errors' => $exception->getMessage(),
            ];

            $GLOBALS['log']->info($exception->getMessage());
        }

        return $result;
    }

    private function extractMessageId($response) {
        if (isset($response->http_response_body)) {
            return $response->http_response_body->id;
        }

        return '';
    }
}