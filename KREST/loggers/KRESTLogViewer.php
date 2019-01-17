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
 * Viewing/Selecting from KREST log (database)
 */
class KRESTLogViewer {

    private $maxLength;

    private $dbTableName = 'syskrestlog';

    # Constructor. Reads settings from config file.
    public function __construct() {

        # Accessing the log file is allowed only for admins:
        if ( !$GLOBALS['current_user']->isAdmin() )
            throw ( new \KREST\ForbiddenException('Forbidden to view the KREST log. Only for admins.'))->setErrorCode('noKRESTlogView');

        $config = SugarConfig::getInstance();
        $this->maxLength = $config->get( 'logger.view.truncateText', 500 ) * 1;

    }

    public function getRoutes() {
        global $db;
        $response = [];
        $sqlResult = $db->query( 'SELECT DISTINCT route FROM syskrestlog ORDER BY route' );
        while ( $row = $db->fetchByAssoc( $sqlResult )) $response[] = array_shift( $row );
        return $response;
    }

    public function getLines( $queryParams, $period = null ) {
        global $db;
        $response = [];

        $whereClauseParts = [];

        if ( $period ) {

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

        }

        $filter = [];
        if ( isset( $queryParams['method']{0} )) $filter[] = 'method = "'.$db->quote($queryParams['method']).'"';
        if ( isset( $queryParams['route']{0} )) $filter[] = 'route = "'.$db->quote($queryParams['method']).'"';
        if ( isset( $queryParams['postParams']{0} )) $filter[] = 'post_params like "%'.$db->quote($queryParams['postParams']).'%"';
        if ( isset( $queryParams['urlParams']{0} )) $filter[] = 'get_params like "%'.$db->quote($queryParams['urlParams']).'%"';
        if ( isset( $queryParams['routeArgs']{0} )) $filter[] = 'args like "%'.$db->quote($queryParams['routeArgs']).'%"';
        if ( isset( $queryParams['response']{0} )) $filter[] = 'response like "%'.$db->quote($queryParams['response']).'%"';
        if ( count( $filter )) $whereClauseParts[] = implode( ' AND ', $filter );

        $whereClause = count( $whereClauseParts ) ? 'WHERE '.implode( ' AND ', $whereClauseParts ):'';

        $limitClause = '';
        if ( isset( $queryParams['limit']{0} )) {
            $queryParams['limit'] *= 1;
            $limitClause = 'LIMIT '.$queryParams['limit'];
        }

        $sql = 'SELECT id, route, method, args as routeArgs, get_params as getParams, user_id as uid, UNIX_TIMESTAMP(requested_at) as dtx, http_status_code as status FROM '.$this->dbTableName.' '.$whereClause.' ORDER BY requested_at DESC '.$limitClause;

        $sqlResult = $db->query( $sql );
        while ( $row = $db->fetchByAssoc( $sqlResult )) {
            $row['pid'] = isset( $row['pid']{0} ) ? (int)$row['pid']:null;
            $row['dtx'] = (float)$row['dtx'];
            $row['status'] = (integer)$row['status'];
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

        $sql = 'SELECT id, route, method, args as routeArgs, get_params as urlParams, post_params as postParams, response, user_id as uid, UNIX_TIMESTAMP(requested_at) as dtx, http_status_code as status FROM '.$this->dbTableName.' WHERE id = "'.$db->quote( $lineId ).'"';

        $line = $db->fetchOne( $sql );
        if ( $line === false )
            throw ( new \KREST\NotFoundException( 'Log line not found.'))->setLookedFor( $lineId );

        return $line;

    }

}