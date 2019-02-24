<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

require_once 'modules/SchedulersJobs/SchedulersJob.php';

class Scheduler extends SugarBean {
    // table columns
    var $id;
    var $deleted;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;
    var $name;
    var $job;
    var $date_time_start;
    var $date_time_end;
    var $job_interval;
    var $time_from;
    var $time_to;
    var $last_run;
    var $status;
    var $catch_up;
    // object attributes
    var $user;
    var $intervalParsed;
    var $intervalHumanReadable;
    var $metricsVar;
    var $metricsVal;
    var $dayInt;
    var $dayLabel;
    var $monthsInt;
    var $monthsLabel;
    var $suffixArray;
    var $datesArray;
    var $scheduledJobs;
    var $timeOutMins = 60;
    // standard SugarBean attrs
    var $table_name				= "schedulers";
    var $object_name			= "Scheduler";
    var $module_dir				= "Schedulers";
    var $new_schema				= true;
    var $process_save_dates 	= true;
    var $order_by;

    public static $job_strings;

    public function __construct($init=true)
    {
        parent::__construct();
        $job = new SchedulersJob();
        $this->job_queue_table = $job->table_name;
    }

    protected function getUser()
    {
        if(empty($this->user)) {
            $this->user = Scheduler::initUser();
        }
        return $this->user;
    }

    /**
     * Function returns an Admin user for running Schedulers or false if no admin users are present in the system
     * (which means the Scheduler Jobs which need admin rights will fail to execute)
     */
    public static function initUser()
    {
        $user = new User();
        $db = DBManagerFactory::getInstance();

        //Check is default admin exists
        $adminId = $db->getOne(
            'SELECT id FROM users WHERE id = ' . $db->quoted('1') . ' AND is_admin = 1 AND deleted = 0 AND status = ' . $db->quoted('Active'),
            true,
            'Error retrieving Admin account info'
        );

        if ($adminId === false) {// Retrieve another admin if default admin doesn't exist
            $adminId = $db->getOne(
                'SELECT id FROM users WHERE is_admin = 1 AND deleted = 0 AND status = ' . $db->quoted('Active'),
                true,
                'Error retrieving Admin account info'
            );
            if ($adminId) {// Get admin user
                $user->retrieve($adminId);
            } else {// Return false and log error
                $GLOBALS['log']->fatal('No Admin account found!');
                return false;
            }
        } else {// Scheduler jobs run as default Admin
            $user->retrieve('1');
        }
        return $user;
    }


    ///////////////////////////////////////////////////////////////////////////
    ////	SCHEDULER HELPER FUNCTIONS

    /**
     * calculates if a job is qualified to run
     */
    public function fireQualified()
    {
        if(empty($this->id)) { // execute only if we have an instance
            $GLOBALS['log']->fatal('Scheduler called fireQualified() in a non-instance');
            return false;
        }

        $now = TimeDate::getInstance()->getNow();
        $now = $now->setTime($now->hour, $now->min, "00")->asDb();
        $validTimes = $this->deriveDBDateTimes($this);

        if(is_array($validTimes) && in_array($now, $validTimes)) {
            $GLOBALS['log']->debug('----->Scheduler found valid job ('.$this->name.') for time GMT('.$now.')');
            return true;
        } else {
            $GLOBALS['log']->debug('----->Scheduler did NOT find valid job ('.$this->name.') for time GMT('.$now.')');
            return false;
        }
    }

    /**
     * Create a job from this scheduler
     * @return SchedulersJob
     */
    public function createJob()
    {
        $job = new SchedulersJob();
        $job->scheduler_id = $this->id;
        $job->name = $this->name;
        $job->execute_time = $GLOBALS['timedate']->nowDb();
        $job->assigned_user_id = $this->getUser()->id;
        $job->target = $this->job;
        return $job;
    }

    /**
     * Checks if any jobs qualify to run at this moment
     * @param SugarJobQueue $queue
     */
    public function checkPendingJobs($queue)
    {
        $allSchedulers = $this->get_full_list('', "schedulers.status='Active' AND NOT EXISTS(SELECT id FROM {$this->job_queue_table} WHERE scheduler_id=schedulers.id AND status!='".SchedulersJob::JOB_STATUS_DONE."')");

        $GLOBALS['log']->info('-----> Scheduler found [ '.count($allSchedulers).' ] ACTIVE jobs');

        if(!empty($allSchedulers)) {
            foreach($allSchedulers as $focus) {
                if($focus->fireQualified()) {
                    $job = $focus->createJob();
                    $queue->submitJob($job, $this->getUser());
                }
            }
        } else {
            $GLOBALS['log']->debug('----->No Schedulers found');
        }
    }

    /**
     * This function takes a Scheduler object and uses its job_interval
     * attribute to derive DB-standard datetime strings, as many as are
     * qualified by its ranges.  The times are from the time of calling the
     * script.
     *
     * @param	$focus		Scheduler object
     * @return	$dateTimes	array loaded with DB datetime strings derived from
     * 						the	 job_interval attribute
     * @return	false		If we the Scheduler is not in scope, return false.
     */
    function deriveDBDateTimes($focus)
    {
        global $timedate;
        $GLOBALS['log']->debug('----->Schedulers->deriveDBDateTimes() got an object of type: '.$focus->object_name);
        /* [min][hr][dates][mon][days] */
        $dateTimes = array();
        $ints	= explode('::', str_replace(' ','',$focus->job_interval));
        $days	= $ints[4];
        $mons	= $ints[3];
        $dates	= $ints[2];
        $hrs	= $ints[1];
        $mins	= $ints[0];
        $today	= getdate($timedate->getNow()->ts);

        // derive day part
        $dayName = array();
        if($days == '*') {
            $GLOBALS['log']->debug('----->got * day');

        } elseif(strstr($days, '*/')) {
            // the "*/x" format is nonsensical for this field
            // do basically nothing.
            $theDay = str_replace('*/','',$days);
            $dayName[] = $theDay;
        } elseif($days != '*') { // got particular day(s)
            if(strstr($days, ',')) {
                $exDays = explode(',',$days);
                foreach($exDays as $k1 => $dayGroup) {
                    if(strstr($dayGroup,'-')) {
                        $exDayGroup = explode('-', $dayGroup); // build up range and iterate through
                        for($i=$exDayGroup[0];$i<=$exDayGroup[1];$i++) {
                            $dayName[] = $i;
                        }
                    } else { // individuals
                        $dayName[] = $dayGroup;
                    }
                }
            } elseif(strstr($days, '-')) {
                $exDayGroup = explode('-', $days); // build up range and iterate through
                for($i=$exDayGroup[0];$i<=$exDayGroup[1];$i++) {
                    $dayName[] = $i;
                }
            } else {
                $dayName[] = $days;
            }

            // check the day to be in scope:
            if(!in_array($today['wday'], $dayName)) {
                return false;
            }
        } else {
            return false;
        }


        // derive months part
        if($mons == '*') {
            $GLOBALS['log']->debug('----->got * months');
        } elseif(strstr($mons, '*/')) {
            $mult = str_replace('*/','',$mons);
            $startMon = $timedate->fromDb(date_time_start)->month;
            $startFrom = ($startMon % $mult);

            $compMons = array();
            for($i=$startFrom;$i<=12;$i+$mult) {
                $compMons[] = $i+$mult;
                $i += $mult;
            }
            // this month is not in one of the multiplier months
            if(!in_array($today['mon'],$compMons)) {
                return false;
            }
        } elseif($mons != '*') {
            $monName = array();
            if(strstr($mons,',')) { // we have particular (groups) of months
                $exMons = explode(',',$mons);
                foreach($exMons as $k1 => $monGroup) {
                    if(strstr($monGroup, '-')) { // we have a range of months
                        $exMonGroup = explode('-',$monGroup);
                        for($i=$exMonGroup[0];$i<=$exMonGroup[1];$i++) {
                            $monName[] = $i;
                        }
                    } else {
                        $monName[] = $monGroup;
                    }
                }
            } elseif(strstr($mons, '-')) {
                $exMonGroup = explode('-', $mons);
                for($i=$exMonGroup[0];$i<=$exMonGroup[1];$i++) {
                    $monName[] = $i;
                }
            } else { // one particular month
                $monName[] = $mons;
            }

            // check that particular months are in scope
            if(!in_array($today['mon'], $monName)) {
                return false;
            }
        }

        // derive dates part
        $dateName = array();
        if($dates == '*') {
            $GLOBALS['log']->debug('----->got * dates');
        } elseif(strstr($dates, '*/')) {
            $mult = str_replace('*/','',$dates);
            $startDate = $timedate->fromDb($focus->date_time_start)->day;
            $startFrom = ($startDate % $mult);

            for($i=$startFrom; $i<=31; $i+$mult) {
                $dateName[] = str_pad(($i+$mult),2,'0',STR_PAD_LEFT);
                $i += $mult;
            }

            if(!in_array($today['mday'], $dateName)) {
                return false;
            }
        } elseif($dates != '*') {
            if(strstr($dates, ',')) {
                $exDates = explode(',', $dates);
                foreach($exDates as $k1 => $dateGroup) {
                    if(strstr($dateGroup, '-')) {
                        $exDateGroup = explode('-', $dateGroup);
                        for($i=$exDateGroup[0];$i<=$exDateGroup[1];$i++) {
                            $dateName[] = $i;
                        }
                    } else {
                        $dateName[] = $dateGroup;
                    }
                }
            } elseif(strstr($dates, '-')) {
                $exDateGroup = explode('-', $dates);
                for($i=$exDateGroup[0];$i<=$exDateGroup[1];$i++) {
                    $dateName[] = $i;
                }
            } else {
                $dateName[] = $dates;
            }

            // check that dates are in scope
            if(!in_array($today['mday'], $dateName)) {
                return false;
            }
        }

        // derive hours part
        //$currentHour = gmdate('G');
        //$currentHour = date('G', strtotime('00:00'));
        $hrName = array();
        if($hrs == '*') {
            $GLOBALS['log']->debug('----->got * hours');
            for($i=0;$i<24; $i++) {
                $hrName[]=$i;
            }
        } elseif(strstr($hrs, '*/')) {
            $mult = str_replace('*/','',$hrs);
            for($i=0; $i<24; $i) { // weird, i know
                $hrName[]=$i;
                $i += $mult;
            }
        } elseif($hrs != '*') {
            if(strstr($hrs, ',')) {
                $exHrs = explode(',',$hrs);
                foreach($exHrs as $k1 => $hrGroup) {
                    if(strstr($hrGroup, '-')) {
                        $exHrGroup = explode('-', $hrGroup);
                        for($i=$exHrGroup[0];$i<=$exHrGroup[1];$i++) {
                            $hrName[] = $i;
                        }
                    } else {
                        $hrName[] = $hrGroup;
                    }
                }
            } elseif(strstr($hrs, '-')) {
                $exHrs = explode('-', $hrs);
                for($i=$exHrs[0];$i<=$exHrs[1];$i++) {
                    $hrName[] = $i;
                }
            } else {
                $hrName[] = $hrs;
            }
        }
        //_pp($hrName);
        // derive minutes
        //$currentMin = date('i');
        $minName = array();
        $currentMin = $timedate->getNow()->format('i'); # ->minute;
        if(substr($currentMin, 0, 1) == '0') {
            $currentMin = substr($currentMin, 1, 1);
        }
        if($mins == '*') {
            $GLOBALS['log']->debug('----->got * mins');
            for($i=0; $i<60; $i++) {
                if(($currentMin + $i) > 59) {
                    $minName[] = ($i + $currentMin - 60);
                } else {
                    $minName[] = ($i+$currentMin);
                }
            }
        } elseif(strstr($mins,'*/')) {
            $mult = str_replace('*/','',$mins);
            $startMin = $timedate->fromDb($focus->date_time_start)->format('i'); # ->minute;
            $startFrom = ($startMin % $mult);
            for($i=$startFrom; $i<=59; $i) {
                if(($currentMin + $i) > 59) {
                    $minName[] = ($i + $currentMin - 60);
                } else {
                    $minName[] = ($i+$currentMin);
                }
                $i += $mult;
            }

        } elseif($mins != '*') {
            if(strstr($mins, ',')) {
                $exMins = explode(',',$mins);
                foreach($exMins as $k1 => $minGroup) {
                    if(strstr($minGroup, '-')) {
                        $exMinGroup = explode('-', $minGroup);
                        for($i=$exMinGroup[0]; $i<=$exMinGroup[1]; $i++) {
                            $minName[] = $i;
                        }
                    } else {
                        $minName[] = $minGroup;
                    }
                }
            } elseif(strstr($mins, '-')) {
                $exMinGroup = explode('-', $mins);
                for($i=$exMinGroup[0]; $i<=$exMinGroup[1]; $i++) {
                    $minName[] = $i;
                }
            } else {
                $minName[] = $mins;
            }
        }
        //_pp($minName);
        // prep some boundaries - these are not in GMT b/c gmt is a 24hour period, possibly bridging 2 local days
        if(empty($focus->time_from)  && empty($focus->time_to) ) {
            $timeFromTs = 0;
            $timeToTs = $timedate->getNow(true)->get('+1 day')->ts;
        } else {
            $tfrom = $timedate->fromDbType($focus->time_from, 'time');
            $timeFromTs = $timedate->getNow(true)->setTime($tfrom->hour, $tfrom->min)->ts;
            $tto = $timedate->fromDbType($focus->time_to, 'time');
            $timeToTs = $timedate->getNow(true)->setTime($tto->hour, $tto->min)->ts;
        }
        $timeToTs++;

        if(empty($focus->last_run)) {
            $lastRunTs = 0;
        } else {
            $lastRunTs = $timedate->fromDb($focus->last_run)->ts;
        }


        /**
         * initialize return array
         */
        $validJobTime = array();

        global $timedate;
        $timeStartTs = $timedate->fromDb($focus->date_time_start)->ts;
        if(!empty($focus->date_time_end)) { // do the same for date_time_end if not empty
            $timeEndTs = $timedate->fromDb($focus->date_time_end)->ts;
        } else {
            $timeEndTs = $timedate->getNow(true)->get('+1 day')->ts;
//			$dateTimeEnd = '2020-12-31 23:59:59'; // if empty, set it to something ridiculous
        }
        $timeEndTs++;
        /*_pp('hours:'); _pp($hrName);_pp('mins:'); _pp($minName);*/
        $dateobj = $timedate->getNow();
        $nowTs = $dateobj->ts;
        $GLOBALS['log']->debug(sprintf("Constraints: start: %s from: %s end: %s to: %s now: %s",
            gmdate('Y-m-d H:i:s', $timeStartTs), gmdate('Y-m-d H:i:s', $timeFromTs), gmdate('Y-m-d H:i:s', $timeEndTs),
            gmdate('Y-m-d H:i:s', $timeToTs), $timedate->nowDb()
        ));
//		_pp('currentHour: '. $currentHour);
//		_pp('timeStartTs: '.date('r',$timeStartTs));
//		_pp('timeFromTs: '.date('r',$timeFromTs));
//		_pp('timeEndTs: '.date('r',$timeEndTs));
//		_pp('timeToTs: '.date('r',$timeToTs));
//		_pp('mktime: '.date('r',mktime()));
//		_pp('timeLastRun: '.date('r',$lastRunTs));
//
//		_pp('hours: ');
//		_pp($hrName);
//		_pp('mins: ');
//		_ppd($minName);
        foreach($hrName as $kHr=>$hr) {
            foreach($minName as $kMin=>$min) {
                $timedate->tzUser($dateobj);
                $dateobj->setTime($hr, $min, 0);
                $tsGmt = $dateobj->ts;

                if( $tsGmt >= $timeStartTs ) { // start is greater than the date specified by admin
                    if( $tsGmt >= $timeFromTs ) { // start is greater than the time_to spec'd by admin
                        if($tsGmt > $lastRunTs) { // start from last run, last run should not be included
                            if( $tsGmt <= $timeEndTs ) { // this is taken care of by the initial query - start is less than the date spec'd by admin
                                if( $tsGmt <= $timeToTs ) { // start is less than the time_to
                                    $validJobTime[] = $dateobj->asDb();
                                } else {
                                    //_pp('Job Time is NOT smaller that TimeTO: '.$tsGmt .'<='. $timeToTs);
                                }
                            } else {
                                //_pp('Job Time is NOT smaller that DateTimeEnd: '.date('Y-m-d H:i:s',$tsGmt) .'<='. $dateTimeEnd); //_pp( $tsGmt .'<='. $timeEndTs );
                            }
                        }
                    } else {
                        //_pp('Job Time is NOT bigger that TimeFrom: '.$tsGmt .'>='. $timeFromTs);
                    }
                } else {
                    //_pp('Job Time is NOT Bigger than DateTimeStart: '.date('Y-m-d H:i',$tsGmt) .'>='. $dateTimeStart);
                }
            }
        }
        //_ppd($validJobTime);
        // need ascending order to compare oldest time to last_run
        sort($validJobTime);
        /**
         * If "Execute If Missed bit is set
         */
        $now = TimeDate::getInstance()->getNow();
        $now = $now->setTime($now->hour, $now->min, "00")->asDb();

        if($focus->catch_up == 1) {
            if($focus->last_run == null) {
                // always "catch-up"
                $validJobTime[] = $now;
            } else {
                // determine what the interval in min/hours is
                // see if last_run is in it
                // if not, add NOW
                if(!empty($validJobTime) && ($focus->last_run < $validJobTime[0]) && ($now > $validJobTime[0])) {
                    // cn: empty() bug 5914;
                    // if(!empty) should be checked, becasue if a scheduler is defined to run every day 4pm, then after 4pm, and it runs as 4pm,
                    // the $validJobTime will be empty, and it should not catch up.
                    // If $focus->last_run is the the day before yesterday,  it should run yesterday and tomorrow,
                    // but it hadn't run yesterday, then it should catch up today.
                    // But today is already filtered out when doing date check before. The catch up will not work on this occasion.
                    // If the scheduler runs at least one time on each day, I think this bug can be avoided.
                    $validJobTime[] = $now;
                }
            }
        }
        return $validJobTime;
    }

    function handleIntervalType($type, $value, $mins, $hours) {
        global $mod_strings;
        /* [0]:min [1]:hour [2]:day of month [3]:month [4]:day of week */
        $days = array (	1 => $mod_strings['LBL_MON'],
            2 => $mod_strings['LBL_TUE'],
            3 => $mod_strings['LBL_WED'],
            4 => $mod_strings['LBL_THU'],
            5 => $mod_strings['LBL_FRI'],
            6 => $mod_strings['LBL_SAT'],
            0 => $mod_strings['LBL_SUN'],
            '*' => $mod_strings['LBL_ALL']);
        switch($type) {
            case 0: // minutes
                if($value == '0') {
                    //return;
                    return trim($mod_strings['LBL_ON_THE']).$mod_strings['LBL_HOUR_SING'];
                } elseif(!preg_match('/[^0-9]/', $hours) && !preg_match('/[^0-9]/', $value)) {
                    return;

                } elseif(preg_match('/\*\//', $value)) {
                    $value = str_replace('*/','',$value);
                    return $value.$mod_strings['LBL_MINUTES'];
                } elseif(!preg_match('[^0-9]', $value)) {
                    return $mod_strings['LBL_ON_THE'].$value.$mod_strings['LBL_MIN_MARK'];
                } else {
                    return $value;
                }
            case 1: // hours
                global $current_user;
                if(preg_match('/\*\//', $value)) { // every [SOME INTERVAL] hours
                    $value = str_replace('*/','',$value);
                    return $value.$mod_strings['LBL_HOUR'];
                } elseif(preg_match('/[^0-9]/', $mins)) { // got a range, or multiple of mins, so we return an 'Hours' label
                    return $value;
                } else {	// got a "minutes" setting, so it will be at some o'clock.
                    $datef = $current_user->getUserDateTimePreferences();
                    return date($datef['time'], strtotime($value.':'.str_pad($mins, 2, '0', STR_PAD_LEFT)));
                }
            case 2: // day of month
                if(preg_match('/\*/', $value)) {
                    return $value;
                } else {
                    return date('jS', strtotime('December '.$value));
                }

            case 3: // months
                return date('F', strtotime('2005-'.$value.'-01'));
            case 4: // days of week
                return $days[$value];
            default:
                return 'bad'; // no condition to touch this branch
        }
    }

    function setIntervalHumanReadable() {
        global $current_user;
        global $mod_strings;

        /* [0]:min [1]:hour [2]:day of month [3]:month [4]:day of week */
        $ints = $this->intervalParsed;
        $intVal = array('-', ',');
        $intSub = array($mod_strings['LBL_RANGE'], $mod_strings['LBL_AND']);
        $intInt = array(0 => $mod_strings['LBL_MINS'], 1 => $mod_strings['LBL_HOUR']);
        $tempInt = '';
        $iteration = '';

        foreach($ints['raw'] as $key => $interval) {
            if($tempInt != $iteration) {
                $tempInt .= '; ';
            }
            $iteration = $tempInt;

            if($interval != '*' && $interval != '*/1') {
                if(false !== strpos($interval, ',')) {
                    $exIndiv = explode(',', $interval);
                    foreach($exIndiv as $val) {
                        if(false !== strpos($val, '-')) {
                            $exRange = explode('-', $val);
                            foreach($exRange as $valRange) {
                                if($tempInt != '') {
                                    $tempInt .= $mod_strings['LBL_AND'];
                                }
                                $tempInt .= $this->handleIntervalType($key, $valRange, $ints['raw'][0], $ints['raw'][1]);
                            }
                        } elseif($tempInt != $iteration) {
                            $tempInt .= $mod_strings['LBL_AND'];
                        }
                        $tempInt .= $this->handleIntervalType($key, $val, $ints['raw'][0], $ints['raw'][1]);
                    }
                } elseif(false !== strpos($interval, '-')) {
                    $exRange = explode('-', $interval);
                    $tempInt .= $mod_strings['LBL_FROM'];
                    $check = $tempInt;

                    foreach($exRange as $val) {
                        if($tempInt == $check) {
                            $tempInt .= $this->handleIntervalType($key, $val, $ints['raw'][0], $ints['raw'][1]);
                            $tempInt .= $mod_strings['LBL_RANGE'];

                        } else {
                            $tempInt .= $this->handleIntervalType($key, $val, $ints['raw'][0], $ints['raw'][1]);
                        }
                    }

                } elseif(false !== strpos($interval, '*/')) {
                    $tempInt .= $mod_strings['LBL_EVERY'];
                    $tempInt .= $this->handleIntervalType($key, $interval, $ints['raw'][0], $ints['raw'][1]);
                } else {
                    $tempInt .= $this->handleIntervalType($key, $interval, $ints['raw'][0], $ints['raw'][1]);
                }
            }
        } // end foreach()

        if($tempInt == '') {
            $this->intervalHumanReadable = $mod_strings['LBL_OFTEN'];
        } else {
            $tempInt = trim($tempInt);
            if(';' == substr($tempInt, (strlen($tempInt)-1), strlen($tempInt))) {
                $tempInt = substr($tempInt, 0, (strlen($tempInt)-1));
            }
            $this->intervalHumanReadable = $tempInt;
        }
    }


    /* take an integer and return its suffix */
    function setStandardArraysAttributes() {
        global $mod_strings;
        global $app_list_strings; // using from month _dom list

        $suffArr = array('','st','nd','rd');
        for($i=1; $i<32; $i++) {
            if($i > 3 && $i < 21) {
                $this->suffixArray[$i] = $i."th";
            } elseif (substr($i,-1,1) < 4 && substr($i,-1,1) > 0) {
                $this->suffixArray[$i] = $i.$suffArr[substr($i,-1,1)];
            } else {
                $this->suffixArray[$i] = $i."th";
            }
            $this->datesArray[$i] = $i;
        }

        $this->dayInt = array('*',1,2,3,4,5,6,0);
        $this->dayLabel = array('*',$mod_strings['LBL_MON'],$mod_strings['LBL_TUE'],$mod_strings['LBL_WED'],$mod_strings['LBL_THU'],$mod_strings['LBL_FRI'],$mod_strings['LBL_SAT'],$mod_strings['LBL_SUN']);
        $this->monthsInt = array(0,1,2,3,4,5,6,7,8,9,10,11,12);
        $this->monthsLabel = $app_list_strings['dom_cal_month_long'];
        $this->metricsVar = array("*", "/", "-", ",");
        $this->metricsVal = array(' every ','',' thru ',' and ');
    }

    /**
     *  takes the serialized interval string and renders it into an array
     */
    function parseInterval() {
        global $metricsVar;
        $ws = array(' ', '\r','\t');
        $blanks = array('','','');

        $intv = $this->job_interval;
        $rawValues = explode('::', $intv);
        $rawProcessed = str_replace($ws,$blanks,$rawValues); // strip all whitespace

        $hours = $rawValues[1].':::'.$rawValues[0];
        $months = $rawValues[3].':::'.$rawValues[2];

        $intA = array (	'raw' => $rawProcessed,
            'hours' => $hours,
            'months' => $months,
        );

        $this->intervalParsed = $intA;
    }

    /**
     * checks for cURL libraries
     */
    function checkCurl() {
        global $mod_strings;

        if(!function_exists('curl_init')) {
            echo '
			<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
				<tr height="20">
					<th width="25%" colspan="2"><slot>
						'.$mod_strings['LBL_WARN_CURL_TITLE'].'
					</slot></td>
				</tr>
				<tr class="oddListRowS1" >
					<td scope="row" valign=TOP width="20%"><slot>
						'.$mod_strings['LBL_WARN_CURL'].'
					<td scope="row" valign=TOP width="80%"><slot>
						<span class=error>'.$mod_strings['LBL_WARN_NO_CURL'].'</span>
					</slot></td>
				</tr>
			</table>
			<br>';
        }
    }

    function displayCronInstructions() {
        global $mod_strings;
        global $sugar_config;
        $error = '';
        if (!isset($_SERVER['Path'])) {
            $_SERVER['Path'] = getenv('Path');
        }
        if(is_windows()) {
            if(isset($_SERVER['Path']) && !empty($_SERVER['Path'])) { // IIS IUSR_xxx may not have access to Path or it is not set
                if(!strpos($_SERVER['Path'], 'php')) {
//					$error = '<em>'.$mod_strings['LBL_NO_PHP_CLI'].'</em>';
                }
            }
        } else {
            if(isset($_SERVER['Path']) && !empty($_SERVER['Path'])) { // some Linux servers do not make this available
                if(!strpos($_SERVER['PATH'], 'php')) {
//					$error = '<em>'.$mod_strings['LBL_NO_PHP_CLI'].'</em>';
                }
            }
        }



        if(is_windows()) {
            echo '<br>';
            echo '
				<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
				<tr height="20">
					<th><slot>
						'.$mod_strings['LBL_CRON_INSTRUCTIONS_WINDOWS'].'
					</slot></th>
				</tr>
				<tr class="evenListRowS1">
					<td scope="row" valign="top" width="70%"><slot>
						'.$mod_strings['LBL_CRON_WINDOWS_DESC'].'<br>
						<b>cd '.realpath('./').'<br>
						php.exe -f cron.php</b>
					</slot></td>
				</tr>
			</table>';
        } else {
            echo '<br>';
            echo '
				<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
				<tr height="20">
					<th><slot>
						'.$mod_strings['LBL_CRON_INSTRUCTIONS_LINUX'].'
					</slot></th>
				</tr>
				<tr>
					<td scope="row" valign=TOP class="oddListRowS1" bgcolor="#fdfdfd" width="70%"><slot>
						'.$mod_strings['LBL_CRON_LINUX_DESC'].'<br>
						<b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;
						cd '.realpath('./').'; php -f cron.php > /dev/null 2>&1</b>
						<br>'.$error.'
					</slot></td>
				</tr>
			</table>';
        }
    }

    /**
     * Archives schedulers of the same functionality, then instantiates new
     * ones.
     */
    function rebuildDefaultSchedulers() {
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Schedulers');
        // truncate scheduler-related tables
        $this->db->query('DELETE FROM schedulers');


        $sched3 = new Scheduler();
        $sched3->name               = $mod_strings['LBL_OOTB_TRACKER'];
        $sched3->job                = 'function::trimTracker';
        $sched3->date_time_start    = create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched3->date_time_end      = create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched3->job_interval       = '0::2::1::*::*';
        $sched3->status             = 'Active';
        $sched3->created_by         = '1';
        $sched3->modified_user_id   = '1';
        $sched3->catch_up           = '1';
        $sched3->save();
        $sched4 = new Scheduler();
        $sched4->name				= $mod_strings['LBL_OOTB_IE'];
        $sched4->job				= 'function::pollMonitoredInboxes';
        $sched4->date_time_start	= create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched4->date_time_end		= create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched4->job_interval		= '*::*::*::*::*';
        $sched4->status				= 'Active';
        $sched4->created_by			= '1';
        $sched4->modified_user_id	= '1';
        $sched4->catch_up			= '0';
        $sched4->save();

        $sched5 = new Scheduler();
        $sched5->name				= $mod_strings['LBL_OOTB_BOUNCE'];
        $sched5->job				= 'function::pollMonitoredInboxesForBouncedCampaignEmails';
        $sched5->date_time_start	= create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched5->date_time_end		= create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched5->job_interval		= '0::2-6::*::*::*';
        $sched5->status				= 'Active';
        $sched5->created_by			= '1';
        $sched5->modified_user_id	= '1';
        $sched5->catch_up			= '1';
        $sched5->save();

        $sched6 = new Scheduler();
        $sched6->name				= $mod_strings['LBL_OOTB_CAMPAIGN'];
        $sched6->job				= 'function::runMassEmailCampaign';
        $sched6->date_time_start	= create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched6->date_time_end		= create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched6->job_interval		= '0::2-6::*::*::*';
        $sched6->status				= 'Active';
        $sched6->created_by			= '1';
        $sched6->modified_user_id	= '1';
        $sched6->catch_up			= '1';
        $sched6->save();


        $sched7 = new Scheduler();
        $sched7->name               = $mod_strings['LBL_OOTB_PRUNE'];
        $sched7->job                = 'function::pruneDatabase';
        $sched7->date_time_start    = create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched7->date_time_end      = create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched7->job_interval       = '0::4::1::*::*';
        $sched7->status             = 'Inactive';
        $sched7->created_by         = '1';
        $sched7->modified_user_id   = '1';
        $sched7->catch_up           = '0';
        $sched7->save();




        $sched12 = new Scheduler();
        $sched12->name               = $mod_strings['LBL_OOTB_SEND_EMAIL_REMINDERS'];
        $sched12->job                = 'function::sendEmailReminders';
        $sched12->date_time_start    = create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched12->date_time_end      = create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched12->job_interval       = '*::*::*::*::*';
        $sched12->status             = 'Active';
        $sched12->created_by         = '1';
        $sched12->modified_user_id   = '1';
        $sched12->catch_up           = '0';
        $sched12->save();

        $sched13 = new Scheduler();
        $sched13->name               = $mod_strings['LBL_OOTB_CLEANUP_QUEUE'];
        $sched13->job                = 'function::cleanJobQueue';
        $sched13->date_time_start    = create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched13->date_time_end      = create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched13->job_interval       = '0::5::*::*::*';
        $sched13->status             = 'Active';
        $sched13->created_by         = '1';
        $sched13->modified_user_id   = '1';
        $sched13->catch_up           = '0';
        $sched13->save();

        $sched14 = new Scheduler();
        $sched14->name              = $mod_strings['LBL_OOTB_REMOVE_DOCUMENTS_FROM_FS'];
        $sched14->job               = 'function::removeDocumentsFromFS';
        $sched14->date_time_start   = create_date(2015, 1, 1) . ' ' . create_time(0, 0, 1);
        $sched14->date_time_end     = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $sched14->job_interval      = '0::3::1::*::*';
        $sched14->status            = 'Active';
        $sched14->created_by        = '1';
        $sched14->modified_user_id  = '1';
        $sched14->catch_up          = '0';
        $sched14->save();

        $sched15 = new Scheduler();
        $sched15->name               = $mod_strings['LBL_OOTB_SUGARFEEDS'];
        $sched15->job                = 'function::trimSugarFeeds';
        $sched15->date_time_start    = create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched15->date_time_end      = create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched15->job_interval       = '0::2::1::*::*';
        $sched15->status             = 'Active';
        $sched15->created_by         = '1';
        $sched15->modified_user_id   = '1';
        $sched15->catch_up           = '1';
        $sched15->save();

        $sched16 = new Scheduler();
        $sched16->name				= $mod_strings['LBL_OOTB_FTS_INDEX'];
        $sched16->job				= 'function::fullTextIndex';
        $sched16->date_time_start	= create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched16->date_time_end		= create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched16->job_interval		= '*/5::*::*::*::*';
        $sched16->status				= 'Inactive';
        $sched16->created_by			= '1';
        $sched16->modified_user_id	= '1';
        $sched16->catch_up			= '0';
        $sched16->save();

        //added 2018-06-06
        $sched17 = new Scheduler();
        $sched17->name				= $mod_strings['LBL_OOTB_SYSLOGSCLEANUP_INDEX'];
        $sched17->job				= 'function::cleanSysLogs';
        $sched17->date_time_start	= create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched17->date_time_end		= create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched17->job_interval		= '40::3::*::*::*';
        $sched17->status				= 'Inactive';
        $sched17->created_by			= '1';
        $sched17->modified_user_id	= '1';
        $sched17->catch_up			= '0';
        $sched17->save();

        //added 2018-06-06
        $sched18 = new Scheduler();
        $sched18->name				= $mod_strings['LBL_OOTB_SYSFTSLOGSCLEANUP_INDEX'];
        $sched18->job				= 'function::cleanSysFTSLogs';
        $sched18->date_time_start	= create_date(2015,1,1) . ' ' . create_time(0,0,1);
        $sched18->date_time_end		= create_date(2030,12,31) . ' ' . create_time(23,59,59);
        $sched18->job_interval		= '50::3::*::*::*';
        $sched18->status				= 'Active';
        $sched18->created_by			= '1';
        $sched18->modified_user_id	= '1';
        $sched18->catch_up			= '0';
        $sched18->save();
    }

    ////	END SCHEDULER HELPER FUNCTIONS
    ///////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////
    ////	STANDARD SUGARBEAN OVERRIDES
    /**
     * function overrides the one in SugarBean.php
     */
    function create_export_query($order_by, $where, $show_deleted = 0) {
        return $this->create_new_list_query($order_by, $where,array(),array(), $show_deleted = 0);
    }

    /**
     * function overrides the one in SugarBean.php
     */

    /**
     * function overrides the one in SugarBean.php
     */
    function fill_in_additional_list_fields() {
        $this->fill_in_additional_detail_fields();
    }

    /**
     * function overrides the one in SugarBean.php
     */
    function fill_in_additional_detail_fields() {
        $this->job_interval_read = $this->getJobIntervalReadValue();
        $this->last_status = $this->getLastStatusValue();
    }

    function getJobIntervalReadValue() {
        global $mod_strings;
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Schedulers');
        $this->parseInterval();
        $this->setIntervalHumanReadable();
        return $this->intervalHumanReadable;
    }

    function getLastStatusValue() {
        global $db;
        $lastStatus = $db->getOne("SELECT status FROM job_queue WHERE scheduler_id = '{$this->id}'  ORDER BY execute_time DESC");
        return $lastStatus ?: '';
    }

    function save($check_notify = false, $fts_index_bean = true)
    {
        $this->job_interval_read = $this->getJobIntervalReadValue();
        return parent::save($check_notify, $fts_index_bean);
    }

    /**
     * function overrides the one in SugarBean.php
     */
    function get_list_view_data()
    {
        global $mod_strings;
        $temp_array = $this->get_list_view_array();
        $temp_array["ENCODED_NAME"]=$this->name;
        $this->parseInterval();
        $this->setIntervalHumanReadable();
        $temp_array['JOB_INTERVAL'] = $this->intervalHumanReadable;
        if($this->date_time_end == '2020-12-31 23:59' || $this->date_time_end == '') {
            $temp_array['DATE_TIME_END'] = $mod_strings['LBL_PERENNIAL'];
        }
        $this->created_by_name = get_assigned_user_name($this->created_by);
        $this->modified_by_name = get_assigned_user_name($this->modified_user_id);
        return $temp_array;

    }

    /**
     * returns the bean name - overrides SugarBean's
     */
    function get_summary_text()
    {
        return $this->name;
    }
    ////	END STANDARD SUGARBEAN OVERRIDES
    ///////////////////////////////////////////////////////////////////////////
    static public function getJobsList()
    {
        if(empty(self::$job_strings)) {
            global $mod_strings;
            include_once('modules/Schedulers/_AddJobsHere.php');

            // job functions
            self::$job_strings = array('url::' => 'URL');
            foreach($job_strings as $k=>$v){
                self::$job_strings['function::' . $v] = $mod_strings['LBL_'.strtoupper($v)];
            }
        }
        return self::$job_strings;
    }
} // end class definition
