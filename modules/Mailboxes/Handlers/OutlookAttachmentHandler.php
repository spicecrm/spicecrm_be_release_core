<?php
namespace SpiceCRM\modules\Mailboxes\Handlers;
use SpiceCRM\includes\SpiceAttachments\SpiceAttachments;

class OutlookAttachmentHandler
{
    public $email;
    public $ewsUrl;
    public $token;
    public $attachments = [];

    public function __construct(\Email $email, $attachmentData) {
        if (!isset($attachmentData['attachmentToken']) || $attachmentData['attachmentToken'] == '') {
            throw new \Exception('Outlook attachment token is missing!');
        }
        if (!isset($attachmentData['ewsUrl']) || $attachmentData['ewsUrl'] == '') {
            throw new \Exception('EWS URL is missing!');
        }
        if (!isset($email)) {
            throw new \Exception('Email is missing!');
        }

        $this->email  = $email;
        $this->ewsUrl = $attachmentData['ewsUrl'];
        $this->token  = $attachmentData['attachmentToken'];

        foreach ($attachmentData['outlookAttachments'] as $item) {
            $attachment = new OutlookAttachment($item, $this->email);
            array_push($this->attachments, $attachment);
        }
    }

    public function saveAttachments() {
        $result = [];

        foreach ($this->attachments as $attachment) {
            // todo check if the attachment already exists
            $attachment->content = $this->getAttachmentContent($attachment);
            $result[$attachment->id] = SpiceAttachments::saveEmailAttachmentFromOutlook($this->email, $attachment);
        }

        return $result;
    }

    private function getAttachmentContent(OutlookAttachment $attachment) {
        $soapAction = 'https://outlook.office365.com/EWS/GetAttachment'; // todo remove hardcode
        $soapString = $attachment->getSoap();
        $errors     = '';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL            => $this->ewsUrl,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $soapString,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: text/xml',
                'SOAPAction: ' . $soapAction,
                'Authorization: Bearer ' . $this->token,
            ],
        ]);

        $response = curl_exec($curl);

        if (isset($response->error)) {
            throw new \Exception($response->error->code . ': ' . $response->error->message);
        }

        if (!$response) {
            throw new \Exception('SOAP XML Response is empty');
        }

        curl_close($curl);

        $ewsResponse = $this->parseSoap($response);

        $attachment->fileMd5  = md5($ewsResponse[$attachment->externalId]['Content']);
        $attachment->fileSize = strlen($ewsResponse[$attachment->externalId]['Content']);

        return $ewsResponse[$attachment->externalId]['Content'];
    }

    private function parseSoap($soapXml) {
        $results = [];

        if ($soapXml) {
            $doc = new \DOMDocument();
            $doc->loadXML($soapXml);

            $attachments = $doc->getElementsByTagName('FileAttachment');

            foreach ($attachments as $attachment) {
                $attachmentArray = [];
                foreach ($attachment->childNodes as $node) {
                    if ($node instanceof \DOMElement) {
                        if ($node->tagName == "t:AttachmentId") {
                            $attachmentArray[$node->localName] = $node->getAttribute('Id');
                        } else {
                            $attachmentArray[$node->localName] = $node->textContent;
                        }

                    }
                }
                $results[$attachmentArray['AttachmentId']] = $attachmentArray;
            }
        } else {
            $errors = libxml_get_errors();
            throw new \Exception('Cannot parse SOAP XML: ' . $errors);
        }

        return $results;
    }
}
