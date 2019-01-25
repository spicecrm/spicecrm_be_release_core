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

/**
 * Viewing/Selecting from database based SugarCRM Log
 */
class LogViewer {

    private static $levelMapping = array(
        'debug'      => 100,
        'info'       => 70,
        'warn'       => 50,
        'deprecated' => 40,
        'error'      => 25,
        'fatal'      => 10,
        'security'   => 5
    );

    private $maxLength;

    private $dbTableName = 'syslogs';

    # Constructor. Reads settings from config file.
    public function __construct() {

        # Accessing the log file is allowed only for admins:
        if ( !$GLOBALS['current_user']->isAdmin() )
            throw ( new \KREST\ForbiddenException('Forbidden to view the CRM log. Only for admins.'))->setErrorCode('noCRMlogView');

        $config = SugarConfig::getInstance();
        $this->maxLength = $config->get( 'logger.view.truncateText', 500 ) * 1;

    }

    private function updateLevelValues() {
        global $db;
        if ( $wert=$db->getOne('SELECT count(*) FROM '.$this->dbTableName.' WHERE level_value IS NULL')) {
            foreach ( self::$levelMapping as $level => $value ) {
                $db->query( $s='UPDATE '.$this->dbTableName.' SET level_value = '.$value.' WHERE level_value IS NULL AND level = "'.$level.'"' );
            }
        }
    }

    public function getLines( $queryParams, $period = null ) {
        global $db;
        $response = [];

        $this->updateLevelValues();

        $whereClauseParts = [];

        if ( $period ) {

            /*
            $periodFilter = [];

            switch ( $period['type'] ) {
                case 'hour': $periodFilter[] = 'HOUR( date_entered ) = '.$period['hour'];
                case 'day': $periodFilter[] = 'DAY( date_entered ) = '.$period['day'];
                case 'month': $periodFilter[] = 'MONTH( date_entered ) = '.$period['month'];
                case 'year': $periodFilter[] = 'YEAR( date_entered ) = '.$period['year'];

            }
            */

            switch ( $period['type'] ) {
                case 'hour':
                    $begin = mktime( $period['hour'], 0, 0, $period['month'], $period['day'], $period['year'] );
                    $afterEnd = mktime( $period['hour']+1, 0, 0, $period['month'], $period['day'], $period['year'] );
                    break;
                case 'day':
                    $begin = mktime( 0, 0, 0, $period['month'], $period['day'], $period['year'] );
                    $afterEnd = mktime( 0, 0, 0, $period['month'], $period['day']+1, $period['year'] );
                    break;
                case 'month':
                    $begin = mktime( 0, 0, 0, $period['month'], 1, $period['year'] );
                    $afterEnd = mktime( 0, 0, 0, $period['month']+1, 1, $period['year'] );
                    break;
                case 'year':
                    $begin = mktime( 0, 0, 0, 1, 1, $period['year'] );
                    $afterEnd = mktime( 0, 0, 0, 1, 1, $period['year']+1 );
                    break;
            }

            $whereClauseParts[] = 'microtime >= '.$begin.' AND microtime < '.$afterEnd;

            # if ( count( $periodFilter )) $whereClauseParts[] = implode( ' AND ', $periodFilter );

        }

        $filter = [];
        if ( isset( $queryParams['userId']{0} )) $filter[] = 'created_by = "'.$db->quote($queryParams['userId']).'"';
        if ( isset( $queryParams['level']{0} )) $filter[] = 'level_value <= '.self::$levelMapping[$queryParams['level']];
        if ( isset( $queryParams['processId']{0} )) $filter[] = 'pid = "'.$db->quote($queryParams['processId']).'"';
        if ( isset( $queryParams['text']{0} )) $filter[] = 'description like "%'.$db->quote($queryParams['text']).'%"';
        if ( count( $filter )) $whereClauseParts[] = implode( ' AND ', $filter );

        $whereClause = count( $whereClauseParts ) ? 'WHERE '.implode( ' AND ', $whereClauseParts ):'';

        $limitClause = '';
        if ( isset( $queryParams['limit']{0} )) {
            $queryParams['limit'] *= 1;
            $limitClause = 'LIMIT '.$queryParams['limit'];
        }

        $sql = 'SELECT id, pid, level as lev, LEFT( description, '.$this->maxLength.' ) AS txt, created_by as uid, if ( LENGTH( description ) <> LENGTH( LEFT( description, '.$this->maxLength.' )), 1, 0 ) AS txtTruncated, microtime as dtx FROM '.$this->dbTableName.' '.$whereClause.' ORDER BY microtime DESC '.$limitClause;

        $GLOBALS['log_viewer_debug_info_sql'] = $sql;
        $sqlResult = $db->query( $sql );
        while ( $row = $db->fetchByAssoc( $sqlResult )) {
            $row['txtTruncated'] = (boolean)$row['txtTruncated'];
            $row['pid'] = isset( $row['pid']{0} ) ? (int)$row['pid']:null;
            $row['dtx'] = (float)$row['dtx'];
            $response[] = $row;
        }

        return $response;
    }

    public function getLinesOfPeriod( $periodType, $criteria, $queryParams ) {
        $period = [ 'type' => $periodType ];
        switch ( $periodType ) {
            case 'hour':    $period['hour'] = $criteria[1];
            case 'day':     $period['day'] = substr( $criteria[0], 6, 2 );
            case 'month':   $period['month'] = substr( $criteria[0], 4, 2 );
            case 'year':    $period['year'] = substr( $criteria[0], 0, 4 );
        }
        return $this->getLines( $queryParams, $period );
    }

    function getFullLine( $lineId ) {
        global $db;

        $sql = 'SELECT id, pid, level as lev, description AS txt, created_by as uid, microtime as dtx FROM '.$this->dbTableName.' WHERE id = "'.$db->quote( $lineId ).'"';

        $line = $db->fetchOne( $sql );
        if ( $line === false )
            throw ( new \KREST\NotFoundException( 'Log line not found.'))->setLookedFor( $lineId );

        return $line;

    }

}