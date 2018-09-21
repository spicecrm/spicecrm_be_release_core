<?php
namespace SpiceCRM\modules\GoogleOAuth;

class SpiceGoogleClient extends \Google_Client
{
    /**
     * Set the auth config from new or deprecated JSON config.
     * This structure should match the file downloaded from
     * the "Download JSON" button on in the Google Developer
     * Console.
     *
     * Added custom logic for the config being a json string
     *
     * @param string|array $config the configuration json
     * @throws Google_Exception
     */
    public function setAuthConfig($config, $is_file = false)
    {
        if (is_string($config)) {

            if ($is_file) {
                if (!file_exists($config)) {
                    throw new InvalidArgumentException('file does not exist');
                }

                $json = file_get_contents($config);
            } else {
                $json = $config;
            }

            if (!$config = json_decode($json, true)) {
                throw new LogicException('invalid json for auth config');
            }
        }

        $key = isset($config['installed']) ? 'installed' : 'web';
        if (isset($config['type']) && $config['type'] == 'service_account') {
            // application default credentials
            $this->useApplicationDefaultCredentials();

            // set the information from the config
            $this->setClientId($config['client_id']);
            $this->config['client_email'] = $config['client_email'];
            $this->config['signing_key'] = $config['private_key'];
            $this->config['signing_algorithm'] = 'HS256';
        } elseif (isset($config[$key])) {
            // old-style
            $this->setClientId($config[$key]['client_id']);
            $this->setClientSecret($config[$key]['client_secret']);
            if (isset($config[$key]['redirect_uris'])) {
                $this->setRedirectUri($config[$key]['redirect_uris'][0]);
            }
        } else {
            // new-style
            $this->setClientId($config['client_id']);
            $this->setClientSecret($config['client_secret']);
            if (isset($config['redirect_uris'])) {
                $this->setRedirectUri($config['redirect_uris'][0]);
            }
        }
    }
}