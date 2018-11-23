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

#require_once('include/SugarLogger/LoggerManager.php');
#require_once('include/SugarLogger/LoggerTemplate.php');

/**
 * Parsing and viewing SugarCRM Log
 */
class LogViewer {

    protected $logfile = 'sugarcrm';
    protected $ext = '.log';
    protected $log_dir = '.';

    private static $levelMapping = array(
        'debug'      => 100,
        'info'       => 70,
        'warn'       => 50,
        'deprecated' => 40,
        'error'      => 25,
        'fatal'      => 10,
        'security'   => 5
    );

    /**
     * Constructor
     * Reads settings from config file
     */
    public function __construct() {
        $this->config = SugarConfig::getInstance();
        $this->ext = $this->config->get('logger.file.ext', $this->ext);
        $this->logfile = $this->config->get('logger.file.name', $this->logfile);
        $this->dateFormat = $this->config->get('logger.file.dateFormat', $this->dateFormat);
        $this->logSize = $this->config->get('logger.file.maxSize', $this->logSize);
        $this->maxLogs = $this->config->get('logger.file.maxLogs', $this->maxLogs);
        $this->filesuffix = $this->config->get('logger.file.suffix', $this->filesuffix);
        $log_dir = $this->config->get('log_dir' , $this->log_dir);
        $this->log_dir = $log_dir . (empty($log_dir)?'':'/');
    }

    public function getLinesOfFile( $filenr, &$allLines, $queryParams, $period = null ) {
        $stop = false;

        $config = SugarConfig::getInstance();
        $maxLength = $config->get( 'logger.view.truncateText', 1000 );

        if ( $filenr > $this->maxLogs ) {
            if ( @$GLOBALS['isREST'] ) throw new \KREST\BadRequestException('Wrong file number.');
            else sugar_die('Wrong file number.');
        }

        $filename = $this->log_dir . $this->logfile . ( $filenr == 0 ? '':'_'.$filenr ) . $this->ext;

        if ( !is_file( $filename )) return [];

        if ( $fp = fopen( $filename, 'r' )) {
            while ( $line = fgets( $fp )) {
                if (( $lineParts = $this->parseLine( $line )) and $this->filterLine( $lineParts, $queryParams )) {
                    if ( $period ) {
                        $datetime = getdate( $lineParts['dtx'] );
                        # Assuming that log files are sorted by date and time!
                        if ( $period['type'] === 'year' ) {
                            if ( $datetime['year'] > $period['year'] ) break;
                            if ( $datetime['year'] < $period['year'] ) continue;
                        } elseif ( $period['type'] === 'month') {
                            if ( $datetime['year'] > $period['year'] ) break;
                            if ( $datetime['year'] < $period['year'] ) continue;
                            if ( $datetime['mon'] > $period['month'] ) break;
                            if ( $datetime['mon'] < $period['month'] ) continue;
                        } elseif ( $period['type'] === 'day') {
                            if ( $datetime['year'] > $period['year'] ) break;
                            if ( $datetime['year'] < $period['year'] ) continue;
                            if ( $datetime['mon'] > $period['month'] ) break;
                            if ( $datetime['mon'] < $period['month'] ) continue;
                            if ( $datetime['mday'] > $period['day'] ) break;
                            if ( $datetime['mday'] < $period['day'] ) continue;
                        } elseif ( $period['type'] === 'hour') {
                            if ( $datetime['year'] > $period['year'] ) break;
                            if ( $datetime['year'] < $period['year'] ) continue;
                            if ( $datetime['mon'] > $period['month'] ) break;
                            if ( $datetime['mon'] < $period['month'] ) continue;
                            if ( $datetime['mday'] > $period['day'] ) break;
                            if ( $datetime['mday'] < $period['day'] ) continue;
                            if ( $datetime['hours'] > $period['hour'] ) break;
                            if ( $datetime['hours'] < $period['hour'] ) continue;
                        }
                    }
                    $lineParts['txt'] = substr( $lineParts['txt'], 0, $maxLength );
                    $lineParts['txtTruncated'] = strlen( $lineParts['txt'] ) > $maxLength;
                    $allLines[] = $lineParts;
                }
            }
            if ( isset( $queryParams['limit']{0} ) and count( $allLines ) > $queryParams['limit'] ) {
                if ( count( $allLines ) - $queryParams['limit'] > 0 ) {
                    array_splice( $allLines, 0, count( $allLines ) - $queryParams['limit'] );
                    $stop = true;
                }
            }
        }

        return !$stop;
    }

    public function getAllLines( $queryParams ) {
        $response = [];
        for ( $i = 0; $i < $this->maxLogs; $i++ ) {
            if ( $this->getLinesOfFile( $i, $response, $queryParams ) === false ) break;
        }
        return $response;
    }

    public function getLinesOfPeriod( $periodType, $criteria, $queryParams ) {
        $response = [];
        $period = [ 'type' => $periodType ];
        switch ( $periodType ) {
            case 'hour':    $period['hour'] = $criteria[1];
            case 'day':     $period['day'] = substr( $criteria[0], 6, 2 );
            case 'month':   $period['month'] = substr( $criteria[0], 4, 2 );
            case 'year':    $period['year'] = substr( $criteria[0], 0, 4 );
        }
        for ( $i = 0; $i < $this->maxLogs; $i++ ) {
            if ( $this->getLinesOfFile( $i, $response, $queryParams, $period ) === false ) break;
        }
        return $response;
    }

    function parseLine( $line ) {
        if ( preg_match('#^(.+)\[(.+)\]\[(.+)\]\[(.+)\](.*)#', $line, $found )) {
            $dummy = $dummy = strtotime( $found[1] );
            return [
                'dtx' => $dummy, // ISO format: ( new DateTime( '@'. ( $dummy )))->format( DATE_ISO8601 )
                'pid' => $found[2],
                'uid' => $found[3],
                'lev' => strtolower( $found[4] ),
                'txt' => trim( $found[5] )
            ];
        } else return false;
    }

    function filterLine( $lineParts, $queryParams ) {
        if ( isset( $queryParams['userId']{0} ) and $queryParams['userId'] !== $lineParts['uid'] ) return false;
        if ( isset( $queryParams['level']{0} ) and self::$levelMapping[$lineParts['lev']] > self::$levelMapping[$queryParams['level']] ) return false;
        if ( isset( $queryParams['processId']{0} ) and $queryParams['processId'] !== $lineParts['pid'] ) return false;
        return true;
    }

    function getFullLine( $filenr, $linenr ) {
        $filename = $this->logfile . ( $filenr == 0 ? '':'_'.$filenr ) . $this->ext;
        $filepath = $this->log_dir . $filename;
        if ( !is_file( $filepath )) {
            $message = 'Log file not found.'.( $filenr > $this->maxLogs ? ' Wrong file number?':'' );
            if ( @$GLOBALS['isREST'] ) throw ( new \KREST\NotFoundException( $message ) )->setLookedFor( $filename );
            else sugar_die( $message );
        }
        if ( $fp = fopen( $filepath, 'r' )) {
            for ( $i = 0; $i <= $linenr; $i++ ) if ( ( $line = fgets( $fp ) ) === false ) {
                if ( @$GLOBALS['isREST'] ) throw ( new \KREST\NotFoundException() )->setLookedFor( "$filename, line $linenr" );
                else return [];
            }
        } else {
            if ( @$GLOBALS['isREST'] ) throw ( new \KREST\Exception( $message ) )->setDetails( $filename );
            else sugar_die( $message );
        }
        return $this->parseLine( $line );
    }

}
