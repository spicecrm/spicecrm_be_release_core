<?php
namespace SpiceCRM\KREST\controllers;

use SpiceCRM\includes\ErrorHandlers\BadRequestException;
use SpiceCRM\includes\ErrorHandlers\Exception;

class gdprController{

    /*
     * Get the GDPR consent text for portal user from the CRM configuration.
     */
    function getPortalGDPRconsentText( $req, $res, $args ) {
        if ( empty( $GLOBALS['sugar_config']['portal_gdpr']['consent_text'] ) or empty( $GLOBALS['sugar_config']['portal_gdpr']['obtain_consent'] ))
            throw ( new Exception('GDPR consent is not configured (yet).'))->setErrorCode('consentNotDefined');

        return $res->withJson([ 'portalGDPRconsentText' => $GLOBALS['sugar_config']['portal_gdpr']['consent_text'] ]);
    }

    /*
     * Saves the GDPR consent of a portal user.
     */
    function setPortalGDPRconsent( $req, $res, $args ) {
        $consentText = $req->getParam('consentText');

        if ( !$GLOBALS['current_user']->portal_only )
            throw ( new BadRequestException('User is not a portal user.'))->setErrorCode('notPortalUser');

        $contactOfPortalUser = new \Contact();
        $contactOfPortalUser->retrieve_by_string_fields([ 'portal_user_id' => $GLOBALS['current_user']->id ]);

        if ( empty( $contactOfPortalUser->id ))
            throw ( new Exception('Contact of portal user not found.'))->setErrorCode('noContactForPortalUser');

        if ( empty( $consentText ))
            throw ( new BadRequestException('Missing consent text.'))->setErrorCode('missingConsentText');

        if ( !empty( $contactOfPortalUser->gdpr_data_source ))
            throw ( new BadRequestException('Consent already set.'))->setErrorCode('consentAlreadySet');

        if ( $GLOBALS['sugar_config']['portal_gdpr']['consent_text'] !== $consentText )
            throw ( new BadRequestException('Wrong consent text.'))->setErrorCode('wrongConsentText')->setDetails(['properConsentText' => $GLOBALS['sugar_config']['portal_gdpr']['consent_text'] ]);

        $contactOfPortalUser->gdpr_data_agreement = true;
        $contactOfPortalUser->gdpr_data_source = gmdate('Y-m-d H:i:s').' (UTC); '.$consentText;
        $contactOfPortalUser->save();

        return $res->withJson(['success' => true, 'text' => $consentText ]);
    }

}
