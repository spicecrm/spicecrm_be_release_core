<?php

namespace SpiceCRM\includes\SpiceReminders\KREST\controllers;

class SpiceRemindersKRESTController{

    static function getReminders($req, $res, $args) {
        return $res->write(json_encode(\SpiceCRM\includes\SpiceReminders\SpiceReminders::getRemindersRaw('', 0)));
    }

    static function addReminder($req, $res, $args){
        \SpiceCRM\includes\SpiceReminders\SpiceReminders::setReminderRaw($args['id'], $args['module'], $args['date']);
        return $res->write(json_encode(array('status' => 'success')));
    }

     static function deleteReminder($req, $res, $args) {
         \SpiceCRM\includes\SpiceReminders\SpiceReminders::removeReminder($args['id']);
        return $res->write(json_encode(array('status' => 'success')));
    }
}