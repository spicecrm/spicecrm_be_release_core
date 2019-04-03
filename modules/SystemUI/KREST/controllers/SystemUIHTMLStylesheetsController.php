<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIHTMLStylesheetsController{

    static public function loadHTMLStyleSheets()
    {
        global $db;
        $response = array('stylesheets' => array());

        $dbResult = $db->query('SELECT id, name, csscode FROM sysuihtmlstylesheets WHERE inactive <> 1');
        while ( $row = $db->fetchByAssoc( $dbResult, false )) {
            $response['stylesheets'][$row['id']] = $row;
        }

        $dbResult = $db->query('SELECT id, name, inline, block, classes, styles, stylesheet_id, wrapper FROM sysuihtmlformats WHERE inactive <> 1 ORDER BY name');
        while( $row = $db->fetchByAssoc( $dbResult, false )) {
            if ( isset( $response['stylesheets'][$row['stylesheet_id']] ) ) {
                $response['stylesheets'][$row['stylesheet_id']]['formats'][] = $row;
            }
        }

        $response['stylesheetsToUse'] = isset($GLOBALS['sugar_config']['htmlStylesheetsToUse']) ? $GLOBALS['sugar_config']['htmlStylesheetsToUse'] : (object)array();

        return $response;
    }

    static public function getHTMLStyleSheets($req, $res, $args)
    {
        return $res->write(json_encode(SystemUIHTMLStylesheetsController::loadHTMLStyleSheets()));
    }
}