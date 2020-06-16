<?php
namespace SpiceCRM\includes\SpiceTemplateCompiler\plugins;

function current_date()
{
    global $current_user;
    $timeFormat = $current_user->getUserDateTimePreferences();

    $now = new \DateTime();
    return $now->format($timeFormat['date']);
}