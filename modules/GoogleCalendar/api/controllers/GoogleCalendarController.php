<?php
namespace SpiceCRM\modules\GoogleCalendar\api\controllers;

use Exception;
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\includes\utils\SpiceUtils;
use SpiceCRM\modules\GoogleCalendar\GoogleCalendar;
use SpiceCRM\includes\authentication\AuthenticationController;
use SpiceCRM\modules\GoogleCalendar\GoogleCalendarRestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use SpiceCRM\includes\SpiceSlim\SpiceResponse as Response;

class GoogleCalendarController
{
    /**
     * Returns the Exchange sync config for the given User.
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function getConfiguration(Request $req, Response $res, array $args): Response {
        $current_user = AuthenticationController::getInstance()->getCurrentUser();
        $db = DBManagerFactory::getInstance();

        // check if we have a userId if not take current user
        $user_id = $args['userId'] ?: $current_user->id;

        // instantiate new calendar to check access
        $calendar = new GoogleCalendar($user_id);

        return $res->withJson([
            'accessible' => $calendar->checkAccess(),
            'cansubscribe' => !empty(SpiceConfig::getInstance()->config['googleapi']['notificationhost']),
            'userconfig' => [$db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteuserconfig WHERE user_id='$user_id'"))],
            'subscriptions' => [$db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteusersubscriptions WHERE user_id='$user_id'"))]
        ]);
    }

    /**
     * Returns the Gsuite sync config for the given User.
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function startSubscription(Request $req, Response $res, array $args): Response {
        $db = DBManagerFactory::getInstance();

        // instantiate new calendar to check access
        $calendar = new GoogleCalendar($args['userId']);
        $subscription = $calendar->startSubscription();

        if($subscription === true){
            $db->query("INSERT INTO sysgsuiteuserconfig (id, user_id, scope)
                VALUES('".SpiceUtils::createGuid()."', '{$args['userId']}', 'Calendar')");
        } else {

        }

        return $res->withJson([
            'userconfig' => [$db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteuserconfig WHERE user_id='{$args['userId']}'"))],
            'subscriptions' => [$db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteusersubscriptions WHERE user_id='{$args['userId']}'"))]
        ]);
    }

    /**
     * Returns the Gsuite sync config for the given User.
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function stopSubscription(Request $req, Response $res, array $args): Response {
        $db = DBManagerFactory::getInstance();

        // instantiate new calendar to check access
        $calendar = new GoogleCalendar($args['userid']);
        $subscription = $calendar->stopSubscription();
        if($subscription === true){
            $db->query("DELETE FROM sysgsuiteuserconfig WHERE user_id = '{$args['userid']}' AND scope = 'Calendar'");
        }

        return $res->withJson([
            'userconfig' => [$db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteuserconfig WHERE user_id='{$args['userid']}'"))],
            'subscriptions' => [$db->fetchByAssoc($db->query("SELECT * FROM sysgsuiteusersubscriptions WHERE user_id='{$args['userid']}'"))]
        ]);
    }

    /**
     * gets a new calendar bean
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws \ReflectionException
     */
    public function getBeans(Request $req, Response $res, array $args): Response {
        $handler = new GoogleCalendarRestHandler();
        $result = $handler->getBeans();
        return $res->withJson($result);
    }

    /**
     * gets a new calendar
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function getCalendars(Request $req, Response $res, array $args): Response {
        $handler = new GoogleCalendarRestHandler();
        $result = $handler->getCalendars();
        return $res->withJson($result);
    }

    /**
     * get the calendar bean mapping
     * 
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function getBeanMappings(Request $req, Response $res, array $args): Response {
        $handler = new GoogleCalendarRestHandler();
        $result = $handler->getBeanMappings();
        return $res->withJson($result);
    }

    /**
     * saves the calender bean mapping
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function saveBeanMappings(Request $req, Response $res, array $args): Response {
        $handler = new GoogleCalendarRestHandler();
        $handler->saveBeanMappings($req->getParsedBody());
        return $res->withStatus(200);
    }

    /**
     * synchronize the google calendar
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function sync(Request $req, Response $res, array $args): Response {
        $handler = new GoogleCalendarRestHandler();

        return $res->withJson($handler->synchronize());
    }

    /**
     * get google calendar events
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function getEvents(Request $req, Response $res, array $args): Response {
        $handler = new GoogleCalendarRestHandler();
        $result = $handler->getGoogleEvents($req->getQueryParams());
        return $res->withJson($result);

    }

}
