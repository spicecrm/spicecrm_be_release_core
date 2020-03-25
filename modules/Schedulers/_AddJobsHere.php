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

require_once dirname(__FILE__).'/../../vendor/autoload.php';

/**
 * Set up an array of Jobs with the appropriate metadata
 * 'jobName' => array (
 * 		'X' => 'name',
 * )
 * 'X' should be an increment of 1
 * 'name' should be the EXACT name of your function
 *
 * Your function should not be passed any parameters
 * Always  return a Boolean. If it does not the Job will not terminate itself
 * after completion, and the webserver will be forced to time-out that Job instance.
 * DO NOT USE sugar_cleanup(); in your function flow or includes.  this will
 * break Schedulers.  That function is called at the foot of cron.php
 */

/**
 * This array provides the Schedulers admin interface with values for its "Job"
 * dropdown menu.
 */
$job_strings = [
	0 => 'refreshJobs',
	1 => 'pollMonitoredInboxes',
	2 => 'runMassEmailCampaign',
    5 => 'pollMonitoredInboxesForBouncedCampaignEmails',
	3 => 'pruneDatabase',
	4 => 'trimTracker',
	/*4 => 'securityAudit()',*/
    12 => 'sendEmailReminders',
    14 => 'cleanJobQueue',
    15 => 'removeDocumentsFromFS',
    16 => 'trimSugarFeeds',
	20 => 'fullTextIndex',
	21 => 'kdeploymentmwnotification',
    22 => 'sendCampaignTaskEmails',
    23 => 'fetchEmails',
    24 => 'processEmails',
    25 => 'processSpiceImports',
    26 => 'cleanSysLogs',
    27 => 'cleanSysFTSLogs',
    28 => 'workflowHandler',
    29 => 'schedulerTest',
	30 => 'fullTextIndexBulk',
	31 => 'generateQuestionnaireEvaluations',
    32 => 'sendEmailScheduleEmails'
];

function workflowHandler(){
    $schedulerHandler = new \SpiceCRM\modules\WorkflowTasks\WorkflowTaskScheduler();
    $schedulerHandler->runScheduledTasks();

    $workflowHandler = new \SpiceCRM\modules\Workflows\WorkflowScheduler();
    $workflowHandler->rundScheduledWorkflows();
    return true;
}

/**
 * Job 0 refreshes all job schedulers at midnight
 * DEPRECATED
 */
function refreshJobs() {
	return true;
}


/**
 * Job 1
 */
function pollMonitoredInboxes() {
    $_bck_up = array('team_id' => $GLOBALS['current_user']->team_id, 'team_set_id' => $GLOBALS['current_user']->team_set_id);
	$GLOBALS['log']->info('----->Scheduler fired job of type pollMonitoredInboxes()');
	global $dictionary;
	global $app_strings;


	require_once('modules/Emails/EmailUI.php');

	$ie = new InboundEmail();
	$emailUI = new EmailUI();
	$r = $ie->db->query('SELECT id, name FROM inbound_email WHERE is_personal = 0 AND deleted=0 AND status=\'Active\' AND mailbox_type != \'bounce\'');
	$GLOBALS['log']->debug('Just got Result from get all Inbounds of Inbound Emails');

	while($a = $ie->db->fetchByAssoc($r)) {
		$GLOBALS['log']->debug('In while loop of Inbound Emails');
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
        $GLOBALS['current_user']->team_id = $ieX->team_id;
        $GLOBALS['current_user']->team_set_id = $ieX->team_set_id;
		$mailboxes = $ieX->mailboxarray;
		foreach($mailboxes as $mbox) {
			$ieX->mailbox = $mbox;
			$newMsgs = array();
			$msgNoToUIDL = array();
			$connectToMailServer = false;
			if ($ieX->isPop3Protocol()) {
				$msgNoToUIDL = $ieX->getPop3NewMessagesToDownloadForCron();
				// get all the keys which are msgnos;
				$newMsgs = array_keys($msgNoToUIDL);
			}
			if($ieX->connectMailserver() == 'true') {
				$connectToMailServer = true;
			} // if
            $GLOBALS['log']->debug("start connecting LINE 87 ");
			$GLOBALS['log']->debug('Trying to connect to mailserver for [ '.$a['name'].' ]');
			if($connectToMailServer) {
				$GLOBALS['log']->debug('Connected to mailserver');
				if (!$ieX->isPop3Protocol()) {
					$newMsgs = $ieX->getNewMessageIds();
				}
                $GLOBALS['log']->debug("got mails:".print_r($newMsgs,true));
				if(is_array($newMsgs)) {
					$current = 1;
					$total = count($newMsgs);
                    $GLOBALS['log']->debug("start processing $total mails from ".$a['name']." LINE 97 ");
					require_once("include/SugarFolders/SugarFolders.php");
					$sugarFolder = new SugarFolder();
					$groupFolderId = $ieX->groupfolder_id;
					$isGroupFolderExists = false;
					$users = array();
					if ($groupFolderId != null && $groupFolderId != "") {
						$sugarFolder->retrieve($groupFolderId);
						$isGroupFolderExists = true;
					} // if
					$messagesToDelete = array();
					if ($ieX->isMailBoxTypeCreateCase()) {
						$users[] = $sugarFolder->assign_to_id;
						$distributionMethod = $ieX->get_stored_options("distrib_method", "");
						if ($distributionMethod != 'roundRobin') {
							$counts = $emailUI->getAssignedEmailsCountForUsers($users);
						} else {
							$lastRobin = $emailUI->getLastRobin($ieX);
						}
						$GLOBALS['log']->debug('distribution method id [ '.$distributionMethod.' ]');
					}
					foreach($newMsgs as $k => $msgNo) {
						$uid = $msgNo;
						if ($ieX->isPop3Protocol()) {
							$uid = $msgNoToUIDL[$msgNo];
						} else {
							$uid = imap_uid($ieX->conn, $msgNo);
						} // else
                        $GLOBALS['log']->debug("Processing mail ".$msgNo." from ".$a['name']." LINE 126 ");
                        if ($isGroupFolderExists) {
							if ($ieX->importOneEmail($msgNo, $uid)) {
								// add to folder
								$sugarFolder->addBean($ieX->email);
                                $GLOBALS['log']->debug("Processing mail ".$msgNo." from ".$a['name']." LINE 131 ");
                                if ($ieX->isPop3Protocol()) {
									$messagesToDelete[] = $msgNo;
								} else {
									$messagesToDelete[] = $uid;
								}
                                //SpiceCRM generic Inbound Mail Processing
                                if(!empty($ieX->processing_file)){
								    $GLOBALS['log']->debug("Processing mail ".$msgNo." from ".$a['name']." in ".$ieX->processing_class." func:".$ieX->processing_function);
								    if(file_exists($ieX->processing_file)) {
                                        require_once($ieX->processing_file);
                                        $processing_class = $ieX->processing_class;
                                        $processing_function = $ieX->processing_function;
                                        if(!empty($processing_class) && !empty($processing_function) && class_exists($processing_class) && method_exists($processing_class,$processing_function)){
                                            $processor = new $processing_class();
                                            $processor->$processing_function($ieX->email);
                                        }
                                    }
                                }
                                //SpiceCRM end
								if ($ieX->isMailBoxTypeCreateCase()) {
									$userId = "";
									if ($distributionMethod == 'roundRobin') {
										if (sizeof($users) == 1) {
											$userId = $users[0];
											$lastRobin = $users[0];
										} else {
											$userIdsKeys = array_flip($users); // now keys are values
											$thisRobinKey = $userIdsKeys[$lastRobin] + 1;
											if(!empty($users[$thisRobinKey])) {
												$userId = $users[$thisRobinKey];
												$lastRobin = $users[$thisRobinKey];
											} else {
												$userId = $users[0];
												$lastRobin = $users[0];
											}
										} // else
									} else {
										if (sizeof($users) == 1) {
											foreach($users as $k => $value) {
												$userId = $value;
											} // foreach
										} else {
											asort($counts); // lowest to highest
											$countsKeys = array_flip($counts); // keys now the 'count of items'
											$leastBusy = array_shift($countsKeys); // user id of lowest item count
											$userId = $leastBusy;
											$counts[$leastBusy] = $counts[$leastBusy] + 1;
										}
									} // else
									$GLOBALS['log']->debug('userId [ '.$userId.' ]');
									$ieX->handleCreateCase($ieX->email, $userId);
								} // if
							} // if
						} else {
								if($ieX->isAutoImport()) {
									$ieX->importOneEmail($msgNo, $uid);
                                    //SpiceCRM generic Inbound Mail Processing
                                    if(!empty($ieX->processing_file)){
                                        $GLOBALS['log']->debug("Processing mail ".$msgNo." from ".$a['name']." in ".$ieX->processing_class." func:".$ieX->processing_function);
                                        if(file_exists($ieX->processing_file)) {
                                            require_once($ieX->processing_file);
                                            $processing_class = $ieX->processing_class;
                                            $processing_function = $ieX->processing_function;
                                            if(!empty($processing_class) && !empty($processing_function) && class_exists($processing_class) && method_exists($processing_class,$processing_function)){
                                                $processor = new $processing_class();
                                                $processor->$processing_function($ieX->email);
                                            }
                                        }
                                    }
                                    //SpiceCRM end
								} else {
									/*If the group folder doesn't exist then download only those messages
									 which has caseid in message*/
									$ieX->getMessagesInEmailCache($msgNo, $uid);
									$email = new Email();
									$header = imap_headerinfo($ieX->conn, $msgNo);
									$email->name = $ieX->handleMimeHeaderDecode($header->subject);
									$email->from_addr = $ieX->convertImapToSugarEmailAddress($header->from);
									$email->reply_to_email  = $ieX->convertImapToSugarEmailAddress($header->reply_to);
									if(!empty($email->reply_to_email)) {
										$contactAddr = $email->reply_to_email;
									} else {
										$contactAddr = $email->from_addr;
									}
									$mailBoxType = $ieX->mailbox_type;
										$ieX->handleAutoresponse($email, $contactAddr);
								} // else
						} // else
						$GLOBALS['log']->debug('***** On message [ '.$current.' of '.$total.' ] *****');
						$current++;
					} // foreach
					// update Inbound Account with last robin
					if ($ieX->isMailBoxTypeCreateCase() && $distributionMethod == 'roundRobin') {
						$emailUI->setLastRobin($ieX, $lastRobin);
					} // if

				} // if
				if ($isGroupFolderExists)	 {
					$leaveMessagesOnMailServer = $ieX->get_stored_options("leaveMessagesOnMailServer", 0);
					if (!$leaveMessagesOnMailServer) {
						if ($ieX->isPop3Protocol()) {
							$ieX->deleteMessageOnMailServerForPop3(implode(",", $messagesToDelete));
						} else {
							$ieX->deleteMessageOnMailServer(implode($app_strings['LBL_EMAIL_DELIMITER'], $messagesToDelete));
						}
					}
				}
			} else {
				$GLOBALS['log']->fatal("SCHEDULERS: could not get an IMAP connection resource for ID [ {$a['id']} ]. Skipping mailbox [ {$a['name']} ].");
				// cn: bug 9171 - continue while
			} // else
		} // foreach
		imap_expunge($ieX->conn);
		imap_close($ieX->conn, CL_EXPUNGE);
	} // while
    $GLOBALS['current_user']->team_id = $_bck_up['team_id'];
    $GLOBALS['current_user']->team_set_id = $_bck_up['team_set_id'];
	return true;
}

/**
 * Job 2
 */
function runMassEmailCampaign() {
	if (!class_exists('LoggerManager')){

	}
	$GLOBALS['log'] = LoggerManager::getLogger('emailmandelivery');
	$GLOBALS['log']->debug('Called:runMassEmailCampaign');

	if (!class_exists('DBManagerFactory')){
		require('include/database/DBManagerFactory.php');
	}

	global $beanList;
	global $beanFiles;
	require("config.php");
	require('include/modules.php');
	if(!class_exists('AclController')) {
		require('modules/ACL/ACLController.php');
	}

	require('modules/EmailMan/EmailManDelivery.php');
	return true;
}

/**
 *  Job 3
 */
function pruneDatabase() {
	$GLOBALS['log']->info('----->Scheduler fired job of type pruneDatabase()');
	$backupDir	= sugar_cached('backups');
	$backupFile	= 'backup-pruneDatabase-GMT0_'.gmdate('Y_m_d-H_i_s', strtotime('now')).'.php';

	$db = DBManagerFactory::getInstance();
	$tables = $db->getTablesArray();
    $queryString = array();

	if(!empty($tables)) {
		foreach($tables as $kTable => $table) {
			// find tables with deleted=1
			$columns = $db->get_columns($table);
			// no deleted - won't delete
			if(empty($columns['deleted'])) continue;

			$custom_columns = array();
			if(array_search($table.'_cstm', $tables)) {
			    $custom_columns = $db->get_columns($table.'_cstm');
			    if(empty($custom_columns['id_c'])) {
			        $custom_columns = array();
			    }
			}

			$qDel = "SELECT * FROM $table WHERE deleted = 1";
			$rDel = $db->query($qDel);

			// make a backup INSERT query if we are deleting.
			while($aDel = $db->fetchByAssoc($rDel, false)) {
				// build column names

				$queryString[] = $db->insertParams($table, $columns, $aDel, null, false);

				if(!empty($custom_columns) && !empty($aDel['id'])) {
                    $qDelCstm = 'SELECT * FROM '.$table.'_cstm WHERE id_c = '.$db->quoted($aDel['id']);
                    $rDelCstm = $db->query($qDelCstm);

                    // make a backup INSERT query if we are deleting.
                    while($aDelCstm = $db->fetchByAssoc($rDelCstm)) {
                        $queryString[] = $db->insertParams($table, $custom_columns, $aDelCstm, null, false);
                    } // end aDel while()

                    $db->query('DELETE FROM '.$table.'_cstm WHERE id_c = '.$db->quoted($aDel['id']));
                }
			} // end aDel while()
			// now do the actual delete
			$db->query('DELETE FROM '.$table.' WHERE deleted = 1');
		} // foreach() tables

		if(!file_exists($backupDir) || !file_exists($backupDir.'/'.$backupFile)) {
			// create directory if not existent
			mkdir_recursive($backupDir, false);
		}
		// write cache file

		write_array_to_file('pruneDatabase', $queryString, $backupDir.'/'.$backupFile);
		return true;
	}
	return false;
}


///**
// * Job 4
// */

//function securityAudit() {
//	// do something
//	return true;
//}

function trimTracker()
{
    global $sugar_config, $timedate;
	$GLOBALS['log']->info('----->Scheduler fired job of type trimTracker()');
	$db = DBManagerFactory::getInstance();

	$admin = new Administration();
	$admin->retrieveSettings('tracker');
	require('modules/Trackers/config.php');
	$trackerConfig = $tracker_config;

    require_once('include/utils/db_utils.php');
    $prune_interval = !empty($admin->settings['tracker_prune_interval']) ? $admin->settings['tracker_prune_interval'] : 30;
	foreach($trackerConfig as $tableName=>$tableConfig) {

		//Skip if table does not exist
		if(!$db->tableExists($tableName)) {
		   continue;
		}

	    $timeStamp = db_convert("'". $timedate->asDb($timedate->getNow()->get("-".$prune_interval." days")) ."'" ,"datetime");
		if($tableName == 'tracker_sessions') {
		   $query = "DELETE FROM $tableName WHERE date_end < $timeStamp";
		} else {
		   $query = "DELETE FROM $tableName WHERE date_modified < $timeStamp";
		}

	    $GLOBALS['log']->info("----->Scheduler is about to trim the $tableName table by running the query $query");
		$db->query($query);
	} //foreach
    return true;
}

/* Job 5
 *
 */
function pollMonitoredInboxesForBouncedCampaignEmails() {
	$GLOBALS['log']->info('----->Scheduler job of type pollMonitoredInboxesForBouncedCampaignEmails()');
	global $dictionary;


	$ie = new InboundEmail();
	$r = $ie->db->query('SELECT id FROM inbound_email WHERE deleted=0 AND status=\'Active\' AND mailbox_type=\'bounce\'');

	while($a = $ie->db->fetchByAssoc($r)) {
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
		$ieX->connectMailserver();
        $ieX->importMessages();
	}

	return true;
}




/**
 * Job 12
 */
function sendEmailReminders(){
	$GLOBALS['log']->info('----->Scheduler fired job of type sendEmailReminders()');
	require_once("modules/Activities/EmailReminder.php");
	$reminder = new EmailReminder();
	return $reminder->process();
}

function removeDocumentsFromFS()
{
    $GLOBALS['log']->info('Starting removal of documents if they are not present in DB');

    /**
     * @var DBManager $db
     * @var SugarBean $bean
     */
    global $db;

    // temp table to store id of files without memory leak
    $tableName = 'cron_remove_documents';

    $resource = $db->limitQuery("SELECT * FROM cron_remove_documents WHERE 1=1 ORDER BY date_modified ASC", 0, 100);
    $return = true;
    while ($row = $db->fetchByAssoc($resource)) {
        $bean = BeanFactory::getBean($row['module']);
        $bean->retrieve($row['bean_id'], true, false);
        if (empty($bean->id)) {
            $isSuccess = true;
            $bean->id = $row['bean_id'];
            $directory = $bean->deleteFileDirectory();
            if (!empty($directory) && is_dir('upload://deleted/' . $directory)) {
                if ($isSuccess = rmdir_recursive('upload://deleted/' . $directory)) {
                    $directory = explode('/', $directory);
                    while (!empty($directory)) {
                        $path = 'upload://deleted/' . implode('/', $directory);
                        if (is_dir($path)) {
                            $directoryIterator = new DirectoryIterator($path);
                            $empty = true;
                            foreach ($directoryIterator as $item) {
                                if ($item->getFilename() == '.' || $item->getFilename() == '..') {
                                    continue;
                                }
                                $empty = false;
                                break;
                            }
                            if ($empty) {
                                rmdir($path);
                            }
                        }
                        array_pop($directory);
                    }
                }
            }
            if ($isSuccess) {
                $db->query('DELETE FROM ' . $tableName . ' WHERE id=' . $db->quoted($row['id']));
            } else {
                $return = false;
            }
        } else {
            $db->query('UPDATE ' . $tableName . ' SET date_modified=' . $db->convert($db->quoted(TimeDate::getInstance()->nowDb()), 'datetime') . ' WHERE id=' . $db->quoted($row['id']));
        }
    }

    return $return;
}


/**
+ * Job 16
+ * this will trim all records in sugarfeeds table that are older than 30 days or specified interval
+ */

function trimSugarFeeds()
{
    global $sugar_config, $timedate;
    $GLOBALS['log']->info('----->Scheduler fired job of type trimSugarFeeds()');
    $db = DBManagerFactory::getInstance();

    //get the pruning interval from globals if it's specified
    $prune_interval = !empty($GLOBALS['sugar_config']['sugarfeed_prune_interval']) && is_numeric($GLOBALS['sugar_config']['sugarfeed_prune_interval']) ? $GLOBALS['sugar_config']['sugarfeed_prune_interval'] : 30;


    //create and run the query to delete the records
    $timeStamp = $db->convert("'". $timedate->asDb($timedate->getNow()->get("-".$prune_interval." days")) ."'" ,"datetime");
    $query = "DELETE FROM sugarfeed WHERE date_modified < $timeStamp";


    $GLOBALS['log']->info("----->Scheduler is about to trim the sugarfeed table by running the query $query");
    $db->query($query);

    return true;
}

/*
 * Job 20 .. rzun the full text indexer
 */

function fullTextIndex(){
    // no date formatting
    global $disable_date_format, $sugar_config;
    $disable_date_format = true;

    // determine package size
    $packagesize = $sugar_config['fts']['schedulerpackagesize'] ?: 5000;

//    require_once('include/SpiceFTSManager/SpiceFTSHandler.php');
    $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
    $ftsHandler->indexBeans($packagesize, true );
    return true;
}

/*
 * Job 30 (20B) .. run the bulk text indexer
 */

function fullTextIndexBulk(){
    // no date formatting
    global $disable_date_format, $sugar_config;
    $disable_date_format = true;

    // determine package size
    $packagesize = $sugar_config['fts']['schedulerpackagesize'] ?: 5000;

//    require_once('include/SpiceFTSManager/SpiceFTSHandler.php');
    $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
    $ftsHandler->bulkIndexBeans($packagesize, null, true );
    return true;
}

/*
 * Job 21 ... send notification before window maintenance starts
 */
function kdeploymentmwnotification(){
    $classpath = 'module/KDeploymentMWs/KDeploymentMWNotification.php';
    if(file_exists('custom/'.$classpath))
        $classpath = 'custom/'.$classpath;
    require_once($classpath);
    $notification = new KDeploymentMWNotification();
    $notification->checkAndNotify();
    return true;
}


function cleanJobQueue($job)
{
    $td = TimeDate::getInstance();
    // soft delete all jobs that are older than cutoff
    $soft_cutoff = 7;
    if(isset($GLOBALS['sugar_config']['jobs']['soft_lifetime'])) {
        $soft_cutoff = $GLOBALS['sugar_config']['jobs']['soft_lifetime'];
    }
    $soft_cutoff_date = $job->db->quoted($td->getNow()->modify("- $soft_cutoff days")->asDb());
    $job->db->query("UPDATE {$job->table_name} SET deleted=1 WHERE status='done' AND date_modified < ".$job->db->convert($soft_cutoff_date, 'datetime'));
    // hard delete all jobs that are older than hard cutoff
    $hard_cutoff = 21;
    if(isset($GLOBALS['sugar_config']['jobs']['hard_lifetime'])) {
        $hard_cutoff = $GLOBALS['sugar_config']['jobs']['hard_lifetime'];
    }
    $hard_cutoff_date = $job->db->quoted($td->getNow()->modify("- $hard_cutoff days")->asDb());
    $job->db->query("DELETE FROM {$job->table_name} WHERE status='done' AND date_modified < ".$job->db->convert($hard_cutoff_date, 'datetime'));
    return true;
}
/**
 * Job 22
 * sendCampaignTaskEmails
 */
function sendCampaignTaskEmails(){
    $campaignTask = BeanFactory::getBean('CampaignTasks');
    return $campaignTask->sendQueuedEmails();
}

/**
 * Job 23
 * fetchEmails
 */
function fetchEmails() {
    require_once(__DIR__ . '/../../vendor/autoload.php');

    $mailboxes = \BeanFactory::getBean('Mailboxes')
        ->get_full_list(
            'mailboxes.name',
            'inbound_comm=1'
        );

    foreach ($mailboxes as $mailbox) {
        $mailbox->initTransportHandler();

        $mailbox->transport_handler->fetchEmails();
    }
}

/**
 * Job 24
 * processEmails
 */
function processEmails() {
    require_once(__DIR__ . '/../../vendor/autoload.php');

    $mailboxes = \BeanFactory::getBean('Mailboxes')
        ->get_full_list(
            'mailboxes.name',
            'inbound_comm=1'
        );

    foreach ($mailboxes as $mailbox) {
        // todo that will most likely need some criteria
        if ($mailbox->load_relationship('emails')) {
            $emails = $mailbox->emails->getBeans();

            foreach ($emails as $email) {
                $email->processEmail();
            }
        }
    }
}

/**
 * Job 25
 * Process SpiceImports Schedules
 */
function processSpiceImports(){
    echo 'importing';
    $import = BeanFactory::getBean('SpiceImports');
    $importList = $import->get_list("date_entered", "spiceimports.status = 'q'", 0 , 5);
    foreach($importList['list'] as $thisImport){

        $thisImport->process();
    }
}

/**
 * Job 26
 * Clean SysLogs
 */

function cleanSysLogs(){
    $defaultInterval = "7 DAY";
    $q = "DELETE FROM syslogs WHERE date_entered < DATE_SUB(now(), INTERVAL ".(isset($GLOBALS['sugar_config']['logger']['db']['clean_interval']) && !empty($GLOBALS['sugar_config']['logger']['db']['clean_interval']) ? $GLOBALS['sugar_config']['logger']['db']['clean_interval'] : $defaultInterval).")";
    $GLOBALS['db']->query($q);
    return true;
}

/**
 * Job 27
 * Clean sysftslogs
 */

function cleanSysFTSLogs(){
    $defaultInterval = "14 DAY";
    $q = "DELETE FROM sysftslog WHERE date_created < DATE_SUB(now(), INTERVAL ".(isset($GLOBALS['sugar_config']['fts']['log_clean_interval']) && !empty($GLOBALS['sugar_config']['fts']['clean_interval']) ? $GLOBALS['sugar_config']['fts']['log_clean_interval'] : $defaultInterval).")";
    $GLOBALS['db']->query($q);
    return true;
}

/**
 * Job 29
 * Test Job, does nothing, for testing of scheduler
 */

function schedulerTest() {
    echo "Scheduler Test (function schedulerTest() executed, nothing else is done.\n";
    $GLOBALS['log']->debug('Scheduler Test');
    return true;
}

/**
 * Job 31
 * Generate missing QuestionnaireEvaluations
 */

function generateQuestionnaireEvaluations()
{
    global $db;
    echo "Generating non existing QuestionnaireEvaluations for ServiceFeedbacks with linked Questionnaires.\n";
    // in future: "FROM questionnaireparticipations instead "FROM servicefeedbacks"
    $result = $db->query('SELECT DISTINCT sf.id FROM servicefeedbacks sf LEFT JOIN questionnaireevaluations qe ON sf.questionnaireevaluation_id = qe.id WHERE NOT ISNULL(NULLIF(sf.questionnaire_id,\'\')) AND ( ISNULL(NULLIF(sf.questionnaireevaluation_id,\'\')) OR qe.deleted = 1 ) AND sf.deleted = 0');
    while ( $row = $db->fetchByAssoc( $result )) {
        QuestionnaireEvaluation::generateEvaluation('ServiceFeedbacks', $row['id'] );
    }
    return true;
}

/**
 * Job 32
 * sendEmailScheduleEmails
 */
function sendEmailScheduleEmails(){
    $emailSchedule = BeanFactory::getBean('EmailSchedules');
    return $emailSchedule->sendQueuedEmails();
}

if($scheduledtaskhandle = opendir('./modules/Schedulers/ScheduledTasks')) {
    while (false !== ($scheduledtaskfile = readdir($scheduledtaskhandle))) {
        if (preg_match('/\.php$/', $scheduledtaskfile)) {
            include('modules/Schedulers/ScheduledTasks/' . $scheduledtaskfile);
        }
    }
}

if (file_exists('modules/Schedulers/ScheduledTasks/scheduledtasks.php')) {
    require('modules/Schedulers/ScheduledTasks/scheduledtasks.php');
}

if (file_exists('custom/modules/Schedulers/_AddJobsHere.php')) {
	require('custom/modules/Schedulers/_AddJobsHere.php');
}

if (file_exists('custom/modules/Schedulers/Ext/ScheduledTasks/scheduledtasks.ext.php'))
{
	require('custom/modules/Schedulers/Ext/ScheduledTasks/scheduledtasks.ext.php');
}

