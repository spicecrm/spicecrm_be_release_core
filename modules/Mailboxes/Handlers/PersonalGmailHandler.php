<?php
namespace SpiceCRM\modules\Mailboxes\Handlers;

use SpiceCRM\modules\GoogleOAuth\GoogleOAuthImpersonation;

class PersonalGmailHandler extends TransportHandler
{
    use GmailApiTrait;

    private $accessToken;
    private $userName;

    private $ssl_verifyhost = false;
    private $ssl_verifypeer = false;


    const API_URL = 'https://gmail.googleapis.com';

    protected $outgoing_settings = [
        'gmail_email_address',
    ];

    /**
     * returns the mailbox name
     *
     * @return string
     */
    public function getMailboxName(){
        global $current_user;
        return "GMail ({$current_user->user_name})";
    }

    protected function initTransportHandler() {
        global $current_user;

        // todo add other options
        $this->userName = $current_user->user_name;

        $oAuth = new GoogleOAuthImpersonation();
        // todo catch exception
        $this->accessToken = (array) $oAuth->getTokenByUserName($this->userName);
        $this->accessToken['expires_at'] = time() + (int) $this->accessToken['expires_in'];
        $this->accessToken['user_id'] = $this->userName;
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
