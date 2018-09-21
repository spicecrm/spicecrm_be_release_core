<?php
namespace SpiceCRM\modules\GoogleCalendar;

interface GoogleCalendarEventInterface {
    public function toEvent();

    public function fromEvent(\Google_Service_Calendar_Event $event);

    public function removeGcalId();
}