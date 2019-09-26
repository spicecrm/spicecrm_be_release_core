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

/**
 * CR1000278: default stage checks packed in a namespace for future configuration
 * Will replace guideChecks.php
 */
namespace SpiceCRM\modules\Opportunities\guideChecks;

class OpportunityGuideChecks {

    public function qualification_activitiy($opportunity)
    {
        $calls = $opportunity->get_linked_beans('calls', 'Call', array(),0, -1, 0, "calls.status = 'held'");
        if(count($calls) > 0)
            return true;

        $meetings = $opportunity->get_linked_beans('meetings', 'Meeting', array(),0, -1, 0, "meetings.status = 'held'");
        if(count($meetings) > 0)
            return true;

        return false;
    }

    public function qualification_projectmanager($opportunity){
        global $db;

        $pmRecord = $db->fetchByAssoc($db->query("SELECT count(id) pmcount FROM opportunities_contacts WHERE deleted = 0 AND contact_role = 'Project Manager' AND opportunity_id = '".$opportunity->id."'"));
        if($pmRecord['pmcount'] > 0)
            return true;

        return false;
    }

    public function qualification_businessneeds($opportunity){
        global $db;

        if(!empty($opportunity->cust_busneeds))
            return true;

        return false;
    }

    public function analysis_activitiy($opportunity){
        $meetings = $opportunity->get_linked_beans('meetings', 'Meeting', array(),0, -1, 0, "meetings.status ='held'");
        if(count($meetings) > 0)
            return true;

        return false;
    }

    public function analysis_businessevaluator($opportunity){
        global $db;

        $pmRecord = $db->fetchByAssoc($db->query("SELECT count(id) pmcount FROM opportunities_contacts WHERE deleted = 0 AND contact_role = 'Business Evaluator' AND opportunity_id = '".$opportunity->id."'"));
        if($pmRecord['pmcount'] > 0)
            return true;

        return false;
    }

    public function analysis_budgetidentified($opportunity){

        if(!empty($opportunity->budget))
            return true;

        return false;
    }

    public function qualification_businesspainpoints($opportunity){
        global $db;

        if(!empty($opportunity->cust_busneeds) && !empty($opportunity->cust_painpoints))
            return true;

        return false;
    }

    public function vprop_valueproposition($opportunity){

        if(!empty($opportunity->cust_busneeds) && !empty($opportunity->cust_painpoints) && !empty($opportunity->cust_solutionproposal) && !empty($opportunity->cust_valueproposition))
            return true;

        return false;
    }

    public function vprop_businessdecisionmaker($opportunity){
        global $db;

        $pmRecord = $db->fetchByAssoc($db->query("SELECT count(id) pmcount FROM opportunities_contacts WHERE deleted = 0 AND contact_role = 'Business Decision Maker' AND opportunity_id = '".$opportunity->id."'"));
        if($pmRecord['pmcount'] > 0)
            return true;

        return false;
    }
}