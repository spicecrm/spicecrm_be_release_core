<?php

namespace SpiceCRM\modules\GoogleOAuth;

use SpiceCRM\modules\GoogleCalendar\GoogleCalendar;

class GoogleOAuthRESTHandler
{
    private $session_params = ['iss', 'sub', 'azp', 'aud', 'iat', 'exp', 'email', 'hd', 'email_verified', 'at_hash',
        'jti', 'name', 'picture', 'given_name', 'family_name', 'locale', 'alg', 'kid', ];

    /**
     * saveToken
     *
     * Verifies the token
     * Starts the user session
     * Saves Google OAuth data in session
     * Authenticates the user in Spice
     *
     * @param array $params
     * @return array|bool|false
     */
    public function saveToken(array $params)
    {
        if (session_id() == '') {
            @session_start();
        }

        try {
            $payload = $this->verifyIdToken($params);
        } catch (\Exception $e) {
            return [
                'result' => false,
                'error'  => $e->getMessage(),
            ];
        }


        if ($payload) {
            $_SESSION['google_oauth']['id_token'] = $params['oauthToken'];
            $_SESSION['google_oauth']['access_token'] = $params['accessToken'];
            foreach ($payload as $key => $value) {
                if (in_array($key, $this->session_params)) {
                    $_SESSION['google_oauth'][$key] = $value;
                }
            }



            try {
                $isAuthenticated = $this->authenticate();
            } catch (\Exception $e) {
                return [
                    'result' => false,
                    'error'  => $e->getMessage(),
                ];
            }

            if ($isAuthenticated) {
                $gcal = new GoogleCalendar();
                $gcal->synchronize();
            }

            return [
                'result' => $isAuthenticated,
            ];
        }

        return $payload;
    }

    /**
     * getToken
     *
     * Returns the Google OAuth data from session
     *
     * @param array $params
     * @return mixed
     */
    public function getToken(array $params)
    {
        return $_SESSION['google_oauth'];
    }

    /**
     * authenticate
     *
     * Authenticates the Google user in Spice
     *
     * @return bool
     * @throws \Exception
     */
    private function authenticate()
    {
        $authController = new \AuthenticationController('OAuthAuthenticate');

        try {
            $isLoginSuccess = $authController->login(
                $_SESSION['google_oauth']['email'],
                '',
                []
            );
        } catch (\Exception $e) {
            throw $e;
        }

        return $isLoginSuccess;
    }

    /**
     * verifyIdToken
     *
     * Google ID Token verification using the Google API Library
     *
     * @param $params
     * @return array|false
     */
    /*private function verifyIdToken($params)
    {
        global $sugar_config;

        $client = new SpiceGoogleClient(['client_id' => $sugar_config['googleapi']['clientid']]);
        $payload = $client->verifyIdToken($params['oauthToken']);

        if (!$payload) {
            throw new \Exception('Cannot verify Google ID Token');
        }

        return $payload;
    }*/

    /**
     * verifyIdToken
     *
     * Google ID Token verification using cURL
     *
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    private function verifyIdToken($params)
    {
        $apiUrl  = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=';
        $apiUrl .= $params['oauthToken'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $apiUrl,
        ]);

        $result = json_decode(curl_exec($curl));

        curl_close($curl);

        if (!$result) {
            throw new \Exception('Cannot verify Google ID Token');
        }

        return $result;
    }
}