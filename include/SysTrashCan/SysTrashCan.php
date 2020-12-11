<?php
/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
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
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/

namespace SpiceCRM\includes\SysTrashCan;

class SysTrashCan
{
    static function addRecord($recordtype, $recodmodule, $recordid, $recordname = '', $linkname = '', $linkmodule = '', $linkid = '', $recorddata = '')
    {
        global $db, $current_user, $timedate;
        $now = $timedate->nowDb();
        $db->query("INSERT INTO systrashcan (id, transactionid, date_deleted, user_deleted, recordtype, recordmodule, recordid, recordname, linkname, linkmodule, linkid, recorddata) VALUES('" . create_guid() . "', '" . $GLOBALS['transactionID'] . "', '$now', '$current_user->id','$recordtype', '$recodmodule', '$recordid', '$recordname', '$linkname', '$linkmodule', '$linkid', '".base64_encode($recorddata)."' )");
    }

    static function getRecords(){
        global $db;

        $retArray = [];

        $records = $db->query("SELECT systrashcan.*, users.user_name FROM systrashcan, users WHERE systrashcan.user_deleted = users.id AND recordtype = 'bean' AND recovered = '0' ORDER BY date_deleted DESC");
        while($record = $db->fetchByAssoc($records)){
            $retArray[] = $record;
        }

        return $retArray;
    }

    static function getRelated($transactionid, $recordid){
        global $db;

        $retArray = [];
        $records = $db->query("SELECT systrashcan.* FROM systrashcan WHERE recordtype = 'related' AND transactionid='$transactionid' AND recordid='$recordid' AND recovered = '0' ORDER BY date_deleted DESC");
        while($record = $db->fetchByAssoc($records)){
            $retArray[] = $record;
        }
        return $retArray;
    }

    static function recover($id, $related){
        global $db, $beanList;

        $record = $db->fetchByAssoc($db->query("SELECT systrashcan.* FROM systrashcan WHERE id='$id' AND recovered = '0'"));

        $bean = array_search($record['recordmodule'], $beanList);
        $focus = \BeanFactory::getBean($bean);
        if($focus->retrieve($record['recordid'], true, false)){
            $focus->mark_undeleted($focus->id);

            if($related){
                $focus->load_relationships();

                // set as recovered
                $db->query("UPDATE systrashcan SET recovered = '1' WHERE id='$id'");

                $relRecords = \SpiceCRM\includes\SysTrashCan\SysTrashCan::getRelated($record['transactionid'], $focus->id);
                foreach($relRecords as $relRecord){
                    if($focus->{$relRecord['linkname']}) {
                        $focus->{$relRecord['linkname']}->add($relRecord['linkid']);
                    }
                    // set as recovered
                    $db->query("UPDATE systrashcan SET recovered = '1' WHERE id='".$relRecord['id']."'");
                }
            }

        } else {
            return 'unable to load bean';
        }

        return true;
    }
}
