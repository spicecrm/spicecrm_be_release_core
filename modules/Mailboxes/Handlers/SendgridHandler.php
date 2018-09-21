<?php
namespace SpiceCRM\modules\Mailboxes\Handlers;

use SendGrid;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Content;

/**
 * Class SendgridHandler
 *
 * In case of problems on windows servers check:
 * https://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
 *
 * @package SpiceCRM\modules\Mailboxes
 */
class SendgridHandler extends TransportHandler
{
    protected $outgoing_settings = [
        'api_key',
        'imap_pop3_username',
    ];

    protected function initTransportHandler()
    {
        $this->transport_handler = new SendGrid($this->mailbox->api_key);
    }

    public function testConnection()
    {
        $response = $this->sendMail(\Email::getTestEmail($this->mailbox));

        return $response;
    }

    protected function composeEmail(\Email $email)
    {
        $from = new Email($this->mailbox->imap_pop3_display_name, $this->mailbox->imap_pop3_username);
        foreach ($email->to() as $recipient) {
            // todo make sure it actually works for multiple recipients
            $to = new Email($recipient['name'], $recipient['email']);
        }
        $subject = $email->name;
        $body    = new Content(
            "text/html",
            $email->body
        );

        $mail = new Mail($from, $subject, $to, $body);

        foreach (json_decode(\SpiceAttachments::getAttachmentsForBean('Emails', $email->id)) as $att) {
            $attachment = new SendGrid\Attachment();
            $attachment->setType($att->file_mime_type);
            $attachment->setDisposition("attachment");
            $attachment->setContentPath("upload://" . $att->filemd5);
            $attachment->setFilename($att->filename);

            $mail->addAttachment($attachment);
        }

        return $mail;
    }

    protected function dispatch($message)
    {
        try {
            $response = $this->transport_handler->client->mail()->send()->post($message);

            if ($response->statusCode() == 202) {
                $result['result'] = true;
                foreach($response->headers() as $header){
                    if(strpos($header, 'X-Message-Id') !== false){
                        $arrayparts = explode(':', $header);
                        $result['message_id'] = trim($arrayparts[1]);
                    }
                }
            } else {
                $result['result'] = false;
                $result['errors'] = json_decode($response->body())->errors[0]->message;
                $GLOBALS['log']->info(json_decode($response->body())->errors[0]->message);
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
}
