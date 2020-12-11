<?php

namespace SpiceCRM\modules\GoogleCalendar;

class GoogleCalendarJobhandler{

    public function renewSubscriptions(){
        global $db, $timedate;

        $handledUserIds = [];

        $now = new \DateTime("now", new \DateTimeZone('UTC'));
        $now->add(new \DateInterval('P1D'));

        $expiredSubscriptions = $db->query("SELECT * FROM sysgsuiteusersubscriptions WHERE expiration <= '".$now->format($timedate->get_db_date_time_format())."'");
        while($expiredSubscription = $db->fetchByAssoc($expiredSubscriptions)){
            $calendar = new GoogleCalendar($expiredSubscription['user_id']);
            if($calendar->stopSubscription() === true) {
                $calendar->startSubscription();
            } else {
                if($calendar->startSubscription() === true) {
                    $db->query("DELETE FROM sysgsuiteusersubscriptions WHERE subscriptionid = '{$expiredSubscription['subscriptionid']}'");
                }
            }

            $handledUserIds[] = $expiredSubscription['user_id'];
        }
    }

}
