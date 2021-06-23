<?php
namespace SpiceCRM\modules\GoogleCalendar\api\controllers;

use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\SpiceSlim\SpiceResponse;
use SpiceCRM\modules\GoogleCalendar\GoogleCalendar;
use Psr\Http\Message\ServerRequestInterface as Request;

class GoogleCalendarWebHooksController
{
    /**
     * Handles the incoming sync requests from the GSuite server.
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    public function handle(Request $req, SpiceResponse $res, array $args) {
        $db = DBManagerFactory::getInstance();

        $GLOBALS['gsuiteinbound'] = true;

        $headers = getallheaders();

        $userRecord = $db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteusersubscriptions WHERE subscriptionid='{$headers['X-Goog-Channel-ID']}' AND resourceid='{$headers['X-Goog-Resource-ID']}'"));
        if ($userRecord) {
            $calendar = new GoogleCalendar($userRecord['user_id']);
            $calendar->syncGcal2Spice($userRecord['user_id']);
        }

        return $res->withJson(['success' => true]);
    }
}