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

namespace SpiceCRM\modules\SystemVardefs;

class SystemVardefs  {

    /**
     * load all dictionaries
     * @param array $dictionary
     * @param string $dictionary_type
     */
    static function loadDictionaries(&$dictionary = array(), $dictionary_type = 'all'){

        $q = "SELECT sysd.id dictionaryid, sysd.name dictionaryname, sysd.sysdictionary_type dictionarytype FROM sysdictionarydefinitions sysd WHERE sysd.deleted = 0 AND sysd.status = 'a' ".($dictionary_type != 'all' ? "  AND sysd.sysdictionary_type='".$dictionary_type."'" : "");
        $q.= " UNION ";
        $q.= "SELECT sysd.id dictionaryid, sysd.name dictionaryname, sysd.sysdictionary_type dictionarytype FROM syscustomdictionarydefinitions sysd WHERE sysd.deleted = 0 AND sysd.status = 'a' ".($dictionary_type != 'all' ? " AND sysd.sysdictionary_type='".$dictionary_type."'" : "");

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                if(isset($dictionary[$row['dictionaryname']])){
                    unset($dictionary[$row['dictionaryname']]);
                }

                switch($row['dictionarytype']){
                    case 'metadata':
                        $dict = self::loadDictionaryMetadata($row['dictionaryid']);
                        $dictionary[$row['dictionaryname']] = $dict[$row['dictionaryname']];
                        break;
                    case 'module':
                        $module = self::getModuleByDictionaryId($row['dictionaryid']);
                        $dict = self::loadDictionaryModule($module);
                        $dictionary[$row['dictionaryname']] = $dict[$row['dictionaryname']];
                        break;
                }
            }
        }
    }

    /**
     * load metadata dictionary
     * @param $dictionary_id
     * @return mixed
     */
    static function loadDictionaryMetadata($dictionary_id)
    {
        $q = "SELECT sysd.id dictionary_id, sysd.name dictionaryname, sysd.tablename, sysd.audited tableaudited, sysd.sysdictionary_type,
        sysdi.name itemname, sysdo.name domainname, sysdof.name technicalname,  
        sysdi.label vname, sysdi.required, sysdi.sysdictionary_ref_id, 
        sysdof.*, sysdov.name validationame

        FROM sysdictionarydefinitions sysd
        LEFT JOIN sysdictionaryitems sysdi ON sysdi.sysdictionarydefinition_id = sysd.id AND sysdi.deleted=0 AND sysdi.status = 'a'
        LEFT JOIN sysdictionaryitems sysdiref ON sysdiref.sysdictionary_ref_id = sysd.id AND sysdiref.deleted=0 AND sysdiref.status='a'
        LEFT JOIN sysdomaindefinitions sysdo ON sysdi.sysdomaindefinition_id = sysdo.id AND sysdo.deleted=0  AND sysdo.status = 'a'
        LEFT JOIN sysdomainfields sysdof ON sysdof.sysdomaindefinition_id = sysdo.id AND sysdo.deleted = 0 AND sysdof.status = 'a' 
        LEFT JOIN sysdomainfieldvalidations sysdov ON sysdov.id = sysdof.sysdomainfieldvalidation_id AND sysdov.deleted = 0 AND sysdov.status = 'a' 
        WHERE sysd.id = '".$dictionary_id."' AND sysd.deleted=0 
                ORDER BY sysdi.sequence ASC
       ";

        return self::loadDictionary($q);
    }

    /**
     * return module nam eaccording to dictionary name (bean name)
     * @param $dictionary_name
     * @return null
     */
    static function getModuleByDictionaryName($dictionary_name){
        $module = null;
        $q = "SELECT module FROM (SELECT * FROM sysmodules UNION SELECT * FROM syscustommodules) as sysmod WHERE sysmod.singular ='{$dictionary_name}' LIMIT 1";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                $module = $row['module'];
            }
        }
        return $module;
    }
    /**
     * return module nam eaccording to dictionary name (bean name)
     * @param $dictionary_name
     * @return null
     */
    static function getModuleByDictionaryId($dictionary_id){
        $module = null;
        $q = "SELECT module FROM (SELECT * FROM sysmodules UNION SELECT * FROM syscustommodules) as sysmod WHERE sysmod.sysdictionarydefinition_id ='{$dictionary_id}' LIMIT 1";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                $module = $row['module'];
            }
        }
        return $module;
    }

    /**
     * return dictionary_id for specified module
     * @param $dictionary_name
     * @return null
     */
    static function getDictionaryIdByModule($module){
        $id = null;
        $q = "SELECT sysdictionarydefinition_id FROM syscustommodules sysmod WHERE sysmod.module ='{$module}' LIMIT 1";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                $id = $row['sysdictionarydefinition_id'];
                return $id;
            }
        }
        $q = "SELECT sysdictionarydefinition_id FROM sysmodules sysmod WHERE sysmod.module ='{$module}' LIMIT 1";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                $id = $row['sysdictionarydefinition_id'];
                return $id;
            }
        }
        return $id;
    }
    /**
     * return module id according to module name
     * @param $module
     * @return string|boolean
     */
    public static function getModuleIdByModuleName($module){
        $q = "SELECT id FROM sysmodules WHERE module='{$module}' UNION SELECT id FROM syscustommodules WHERE module='{$module}'";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                return $row['id'];
            }
        }
        return false;
    }

    /**
     * return module name according to module id
     * @param $module_id
     * @return string|boolean
     */
    public static function getModuleNameByModuleId($module_id){
        $q = "SELECT module FROM sysmodules WHERE id='{$module_id}' UNION SELECT module FROM syscustommodules WHERE id='{$module_id}'";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                return $row['module'];
            }
        }
        return false;
    }

    /**
     * returns dictionary information about a module
     * @param $module
     * @return false|array
     */
    public static function getModuleDictionaryMainDataByModuleName($module){
        $q = "SELECT sysmod.id sysmodule_id, sysmod.module, sysd.id sysdictionary_id, sysd.tablename
FROM (select * from sysmodules UNION SELECT * FROM syscustommodules ) sysmod
INNER JOIN (select * from sysdictionarydefinitions UNION select * from syscustomdictionarydefinitions) sysd ON sysd.id = sysmod.sysdictionarydefinition_id
 WHERE sysd.sysdictionary_type='module' AND sysmod.module='{$module}'
 GROUP BY sysmod.module";

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                return $row;
            }
        }
        return false;
    }

    /**
     * load module dictionary
     * @param $module
     * @return mixed
     */
    static function loadDictionaryModule($module)
    {
        $dictionary_id = self::getDictionaryIdByModule($module);

        $q = "SELECT sysd.id dictionary_id, sysd.name dictionaryname, sysd.tablename, sysd.audited tableaudited, sysd.sysdictionary_type,
         sysdo.name domainname, sysdof.name technicalname, 
        sysdi.name itemname, sysdi.label vname, sysdi.required,  sysdi.sysdictionary_ref_id,
        sysdof.*, sysdov.name validationame
        FROM (SELECT * from sysdictionarydefinitions UNION SELECT * from syscustomdictionarydefinitions) sysd
        LEFT JOIN (SELECT * from sysdictionaryitems UNION SELECT * from syscustomdictionaryitems) sysdi ON sysdi.sysdictionarydefinition_id = sysd.id
        LEFT JOIN (SELECT * from sysdictionaryitems UNION SELECT * from syscustomdictionaryitems) sysdiref ON sysdiref.sysdictionary_ref_id = sysd.id
        LEFT JOIN (SELECT * from sysdomaindefinitions UNION SELECT * from syscustomdomaindefinitions) sysdo ON sysdi.sysdomaindefinition_id = sysdo.id
        LEFT JOIN (SELECT * from sysdomainfields UNION SELECT * from syscustomdomainfields)  sysdof ON sysdof.sysdomaindefinition_id = sysdo.id
        LEFT JOIN (SELECT * from sysdomainfieldvalidations UNION SELECT * from syscustomdomainfieldvalidations) sysdov ON sysdov.id = sysdof.sysdomainfieldvalidation_id
        WHERE sysd.id = '{$dictionary_id}'
        ORDER BY sysdi.sequence ASC
       ";

        $dict =  self::loadDictionary($q, $module);

        return $dict;
    }


    /**
     * parses definitions for selected dictionary
     * @param $q
     * @param $module context module
     * @return mixed
     */
    static function loadDictionary($q, $module = null){

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){

                $dictionary_name = $row['dictionaryname'];
                $dictionary[$dictionary_name]['type'] = $row['sysdictionary_type'];
                $dictionary[$dictionary_name]['table'] = $row['tablename'];
                if(!empty($row['module'])){
                    $dictionary[$dictionary_name]['module'] = $row['module'];
                }
                $dictionary[$dictionary_name]['audited'] = (bool)$row['tableaudited'];
                $dictionary[$dictionary_name]['unified_search'] = (bool)$row['unified_search'];
    //            $dictionary[$dictionary_name]['full_text_search'] = (bool)$row['full_text_search'];
    //            $dictionary[$dictionary_name]['unified_search_default_enabled'] = (bool)$row['unified_search_default_enabled'];
    //            $dictionary[$dictionary_name]['duplicate_merge'] = (bool)$row['duplicate_merge'];

                //if ref_id load fields from ref dictionary
                if(!empty($row['sysdictionary_ref_id'])){
                    self::loadDictionaryFields($row['sysdictionary_ref_id'], $dictionary[$dictionary_name]['fields'], $row['module']);
//                    $dictionary_id = $row['sysdictionary_ref_id'];
                }else{
                    self::parseFieldDefinition($row, $dictionary[$dictionary_name]['fields'], $module);
                }
                // load links / relate fields
                self::loadLinksForDictionary($dictionary[$dictionary_name], $module);

                $dictionary[$dictionary_name]['indices'] = self::loadDictionaryIndices($row['dictionary_id']);

            }
        }

        return $dictionary;
    }


    /**
     * @param $dictionary_id
     * @param $dict
     * @param null $module
     */
    static function loadDictionaryFields($dictionary_id, &$dict, $module = null){
        $q = "SELECT sysd.tablename, sysd.audited tableaudited, sysd.id dictionary_id, sysd.sysdictionary_type,
sysdi.name itemname, sysdo.name domainname, sysdof.id , sysdof.name technicalname, 
 sysdi.label vname, sysdi.exclude_from_audited, sysdi.required, sysdi.sysdictionary_ref_id, 
sysdof.*, sysdov.id sysdomainfieldvalidation_id , sysdov.validation_type 
        FROM sysdictionarydefinitions sysd
        LEFT JOIN sysdictionaryitems sysdi ON sysdi.sysdictionarydefinition_id = sysd.id AND sysdi.deleted = 0 AND sysdi.status = 'a'
        LEFT JOIN sysdictionaryitems sysdiref ON sysdiref.sysdictionary_ref_id = sysd.id AND sysdiref.deleted = 0 AND sysdiref.status = 'a'
                LEFT JOIN sysdomaindefinitions sysdo ON sysdi.sysdomaindefinition_id = sysdo.id AND sysdo.deleted = 0 AND sysdo.status = 'a'
        LEFT JOIN sysdomainfields sysdof ON sysdof.sysdomaindefinition_id = sysdo.id AND sysdof.deleted = 0 AND sysdof.status = 'a'        
        LEFT JOIN sysdomainfieldvalidations sysdov ON sysdov.id = sysdof.sysdomainfieldvalidation_id AND sysdov.deleted = 0 AND sysdov.status = 'a'     
        WHERE sysd.id= '{$dictionary_id}'
               GROUP BY sysdi.id
					ORDER BY sysdi.sequence ASC";

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                self::parseFieldDefinition($row, $dict, $module);
            }
        }
    }

    /**
     * @param $row
     * @param $dict
     * @param null $module
     */
    static function parseFieldDefinition($row, &$dict, $module = null){
        if(empty($row['name'])) return;

        //vardefs
        //build variable name
        $fieldname = self::parseFieldName($row);
        $dict[$fieldname] = array();
        $dict[$fieldname]['name'] = $fieldname;
        $dict[$fieldname]['vname'] = (!empty($row['vname']) ? $row['vname'] : $row['label']);
        $dict[$fieldname]['type'] = (!empty($row['fieldtype']) ? $row['fieldtype'] : $row['dbtype']);
        if(!empty($row['len'])) {
            $dict[$fieldname]['len'] = $row['len'];
        }
        if($dict[$fieldname]['type'] != $row['dbtype'] && !is_null($row['dbtype'])) {
            $dict[$fieldname]['dbtype'] = $row['dbtype'];
        }
        if(!empty($row['fieldsource'])){
            $dict[$fieldname]['source'] = $row['fieldsource'];
        }
//        if(!$row['dbtype']){
//            $dict[$fieldname]['source'] = 'non-db';
//        }

         $dict[$fieldname]['audited'] = (bool)!$row['exclude_from_audited'];

//        if(!empty($row['enable_range_search'])) {
//            $dict[$fieldname]['enable_range_search'] = (bool)$row['enable_range_search'];
//        }
//        if(!empty($row['link'])) {
//            $dict[$fieldname]['link'] = $row['link'];
//        }
//        if(!empty($row['rname'])) {
//            $dict[$fieldname]['rname'] = $row['rname'];
//        }
//        if(!empty($row['id_name'])) {
//            $dict[$fieldname]['id_name'] = $row['id_name'];
//        }
        if(!empty($row['non_db'])) {
            $dict[$fieldname]['source'] = 'non-db';
        }
//        if(!empty($row['parent_type'])) {
//            $dict[$fieldname]['parent_type'] = $row['parent_type'];
//        }
//        if(!empty($row['parent_name'])) {
//            $dict[$fieldname]['parent_name'] = $row['parent_name'];
//        }
        if($dict[$fieldname]['type'] == 'enum' || $dict[$fieldname]['type'] == 'multienum' || $dict[$fieldname]['type'] == 'radio'){
            $sysvalidation = self::getSysDomainFieldValidationBySysDomainId($row['sysdomaindefinition_id']);
            if($sysvalidation['validation_type'] == 'options'){
                $dict[$fieldname]['options'] = $sysvalidation['name'];
            }
            if($sysvalidation['validation_type'] == 'function'){
                $dict[$fieldname]['function_name'] = $sysvalidation['function_name'];
//                $dict[$fieldname]['function_returns'] = $sysvalidation['function_returns'];
            }

        }

        // enrich module
        if($dict[$fieldname]['type'] == 'relate'){
            if(isset($row['linked_module'])){
                $dict[$fieldname]['module'] = $row['linked_module'];
            }
        }

        //check on group
//        if(!empty($row['fieldgroup'])) {
//            $dict[$fieldname]['group'] = self::parseGroupName($row);
//        }

        //check on validation for options, ranges...
        if(!empty($row['sysdomainfieldvalidation_id'])){
            switch($row['validation_type']){
                case 'options':

                    break;
                case 'range':
                    break;
            }
        }

        if(!empty($row['description'])){
            $dict[$fieldname]['comment'] = $row['description'];
        }


    }


    /**
     * @param $dictionary_id
     * @return array
     */
    static function loadDictionaryIndices($dictionary_id){
        $indices = [];
        $q = "SELECT sysdx.name, sysdx.indextype, GROUP_CONCAT(sysdi.name) indexfields
        FROM sysdictionaryindexes sysdx
        LEFT JOIN sysdictionaryindexitems sysdxi ON sysdxi.sysdictionaryindex_id = sysdx.id
        LEFT JOIN sysdictionarydefinitions sysd ON sysd.id = sysdx.sysdictionarydefinition_id      
        LEFT JOIN sysdictionaryitems sysdi on sysdi.id = sysdxi.sysdictionaryitem_id 
        LEFT JOIN sysdictionaryitems sysdiref on sysdiref.sysdictionarydefinition_id = sysdi.sysdictionary_ref_id
        WHERE sysdx.sysdictionarydefinition_id = '{$dictionary_id}'
        GROUP BY sysdx.id
        ORDER BY sysdx.name ASC
";

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                $indices[] = self::parseIndexDefinition($row);
            }
        }
        return $indices;
    }

    /**
     * build array definition
     * @param $row
     * @return array|void
     */
    static function parseIndexDefinition($row){
        if(empty($row['name'])) return;
        $index = [];
        $index['name'] = $row['name'];
        $index['type'] = $row['indextype'];
        $index['fields'] = explode(",", $row['indexfields']);
        return $index;
    }


    /**
     * Creates link and relate field definitions
     * one is the module having children - many is the module having the id of the parent
     * example Account <> ServiceTickets: accounts.id = servicetickets.account_id. Thefore Accounts is one = left, ServiceTickets is many = right
     * @param $dictionary_id
     * @param $module
     * @return array
     */
    static function getRelationshipDefinitionsForDictionary($module){
        $moduleId = self::getModuleIdByModuleName($module);
        $q = "SELECT rel.id, rel.relationship_name, rel.reverse, rel.relationship_type, rel.relationship_role_column, rel.relationship_role_column_value,
lhs_sysm.module lhs_module, rhs_sysm.module rhs_module, lhs_dict.tablename lhs_table, rhs_dict.tablename rhs_table
FROM relationships rel       
LEFT JOIN (select * from sysmodules union select * from syscustommodules) lhs_sysm ON lhs_sysm.id = rel.lhs_sysmodule_id 
LEFT JOIN (select * from sysdictionarydefinitions union select * from syscustomdictionarydefinitions) lhs_dict ON lhs_dict.id = lhs_sysm.sysdictionarydefinition_id
LEFT JOIN (select * from sysmodules union select * from syscustommodules) rhs_sysm ON rhs_sysm.id = rel.rhs_sysmodule_id 
LEFT JOIN (select * from sysdictionarydefinitions union select * from syscustomdictionarydefinitions) rhs_dict ON rhs_dict.id = rhs_dict.sysdictionarydefinition_id
 WHERE rel.deleted=0 AND (rel.lhs_sysmodule_id = '{$moduleId}' OR rel.rhs_sysmodule_id = '{$moduleId}') GROUP BY rel.id";
//        $q = "SELECT rel.* FROM (select * from sysdictionaryrelationships union select * from syscustomdictionaryrelationships) rel
//                INNER JOIN (select * from sysmodules union select * from syscustommodules) sysm ON sysm.id = rel.lhs_sysmodule_id OR sysm.id = rel.rhs_sysmodule_id
//            WHERE rel.deleted=0 AND rel.lhs_sysmodule_id = '{$moduleId}' OR rel.rhs_sysmodule_id = '{$moduleId}' GROUP BY rel.id";
//        if($moduleId == 'fe6e0ecf-8f13-4f34-abe5-733ab487b2ed')
//            die('<pre>'.$moduleId.print_r($q, true));



        if($res = $GLOBALS['db']->query($q)) {
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                // get sides
                $side = self::getSide($module, $row);
                $oppositeside = REL_LHS;
                if($oppositeside == $side) $oppositeside = REL_RHS;

                // catch when relationship is on itself like accounts.id > accounts.parent_id
                // in that case we'll need to create vardefs for the other side in same dictionary
                $self_referencing = false;
                if($row['lhs_module'] == $row['rhs_module']){
                    $self_referencing = true;
                }

                // generate vardefs from relationship definition
                $vardefs = self::getVardefsFromRelationship($row, $side, $oppositeside);

                // self-referencing
                if($self_referencing){
                    $selfvardefs = self::getVardefsFromRelationship($row, $oppositeside, $side);
                    if(!empty($selfvardefs)){
                        $vardefs = array_merge($vardefs, $selfvardefs);
                    }
                }
            }
        }

        return $vardefs;
    }

    /**
     * create vardefs according to relationship definition
     * @param $row
     * @param $side
     * @param $oppositeside
     * @return array
     */
    static function getVardefsFromRelationship($row, $side, $oppositeside){
        $vardefs = [];
        $sidename = strtolower($side);
        $oppositesidename = strtolower($oppositeside);

        // create $side vardefs
        if($side == REL_RHS) {
            if ($row[$sidename . '_key']) {
                $vardefs[$row[$sidename . '_key']] = [
                    'name' => $row[$sidename . '_key'],
                    'type' => 'id',
                ];
            }
            if ($row[$sidename . '_linkname']) {
                $vardefs[$row[$sidename . '_linkname']] = [
                    'name' => $row[$sidename . '_linkname'],
                    'vname' => $row[$sidename . '_vname'],
                    'type' => 'link',
                    'source' => 'non-db',
                    'relationship' => $row['relationship_name'],
                    'module' => $row[$oppositesidename . '_module'],
                    'default' => !empty($row[$sidename . '_default']) ? true : false,
                ];
            }
            if ($row[$sidename . '_relatename']) {
                $vardefs[$row[$sidename . '_relatename']] = [
                    'name' => $row[$sidename . '_relatename'],
                    'rname' => $row[$sidename . '_realname'],
                    'id_name' => $row[$sidename . '_key'],
                    'type' => 'relate',
                    'source' => 'non-db',
                    'link' => $row[$sidename . '_linkname'],
                    'module' => $row[$oppositesidename . '_module'],
                    'join_name' => (!empty($row['join_name']) ? $row['join_name'] : $row[$oppositesidename . '_table']),
                ];
            }
        }

        if($side == REL_LHS) {
            if ($row[$sidename . '_key'] != 'id') {
                $vardefs[$row[$sidename . '_key']] = [
                    'name' => $row[$sidename . '_key'],
                    'type' => 'id', //@todo: not always named id!
                ];
            }
            if ($row[$sidename . '_linkname']) {
                $vardefs[$row[$sidename . '_linkname']] = [
                    'name' => $row[$sidename . '_linkname'],
                    'vname' => $row[$sidename . '_vname'],
                    'type' => 'link',
                    'source' => 'non-db',
                    'relationship' => $row['relationship_name'],
                    'module' => $row[$oppositesidename . '_module'],
                    'default' => !empty($row[$sidename . '_default']) ? true : false,
                ];
            }

        }

        return $vardefs;
    }

    /**
     * determines wether origin module is left or right side in relationship
     * @param $origin_module
     * @param $row
     * @return string
     */
    static function getSide($origin_module, $row){
        if($row['rhs_module'] == $origin_module){
            return REL_RHS;
        }
        return REL_LHS;
    }

    /**
     * load full relationships
     * @return array
     */
    public static function loadRelationships(){
        $relationships = [];
        $q = "SELECT rel.*, join_dicts.tablename join_table,
lhs_sysm.module lhs_module, lhs_sysm.bean lhs_bean, lhs_dicts.tablename lhs_table, lhs_dicts.sysdictionary_type lhs_dictionary_type, lhs_sysdictitems.name lhs_key, lhs_sysm.module_label lhs_module_label, join_lhs_sysdictitems.name join_key_lhs,
rhs_sysm.module rhs_module, rhs_sysm.bean rhs_bean, rhs_dicts.tablename rhs_table, rhs_dicts.sysdictionary_type rhs_dictionary_type, rhs_sysdictitems.name rhs_key, rhs_sysm.module_label rhs_module_label, join_rhs_sysdictitems.name join_key_rhs
    
    FROM ( select * from sysdictionaryrelationships union select * from syscustomdictionaryrelationships) rel
   
   LEFT JOIN (select * from sysmodules union select * from syscustommodules) lhs_sysm ON lhs_sysm.sysdictionarydefinition_id = rel.lhs_sysdictionarydefinition_id  
   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) lhs_sysdictitems ON lhs_sysdictitems.id = rel.lhs_sysdictionaryitem_id
   LEFT JOIN (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) lhs_dicts ON lhs_dicts.id = rel.lhs_sysdictionarydefinition_id
   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) join_lhs_sysdictitems ON join_lhs_sysdictitems.id = rel.join_lhs_sysdictionaryitem_id
   
   LEFT JOIN (select * from sysmodules union select * from syscustommodules) rhs_sysm ON rhs_sysm.sysdictionarydefinition_id = rel.rhs_sysdictionarydefinition_id  
   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) rhs_sysdictitems ON rhs_sysdictitems.id = rel.rhs_sysdictionaryitem_id
   LEFT JOIN (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) rhs_dicts ON rhs_dicts.id = rel.rhs_sysdictionarydefinition_id
   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) join_rhs_sysdictitems ON join_rhs_sysdictitems.id = rel.join_rhs_sysdictionaryitem_id
      
      LEFT JOIN (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) join_dicts ON join_dicts.id = rel.join_sysdictionarydefinition_id
      
   WHERE rel.deleted=0 
	GROUP BY rel.id
";
        if($res = $GLOBALS['db']->query($q)) {
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {

                $rel = [];
                // id
                $rel['id'] = $row['id'];
                // relationship type
                $rel['relationship_type'] = $row['relationship_type'];

                // left side will contain all informations
                $rel['lhs_module'] = $row['lhs_module'];
                $rel['lhs_table'] = $row['lhs_table'];
                $rel['lhs_key'] = $row['lhs_key'];
                $rel['lhs_sysdictionarydefinition_id'] = $row['lhs_sysdictionarydefinition_id'];
                $rel['lhs_linkname'] = $row['lhs_linkname'];
                if(!empty($row['rhs_table'])){
                    $rel['lhs_linkname'] = str_replace('{rhs_table}', $row['rhs_table'], $rel['lhs_linkname']);
                }

                // right side may not. We'll have t query up to find module involved
                $rel['rhs_module'] = $row['rhs_module'];
                $rel['rhs_table'] = $row['rhs_table'];
                $rel['rhs_key'] = $row['rhs_key'];
                $rel['rhs_sysdictionarydefinition_id'] = $row['rhs_sysdictionarydefinition_id'];
                $rel['rhs_linkname'] = $row['rhs_linkname'];
                $rel['rhs_relatename'] = $row['rhs_relatename'];
                if(!empty($row['lhs_table'])){
                    $rel['rhs_linkname'] = str_replace('{lhs_table}', $row['lhs_table'], $rel['rhs_linkname']);
                }

                // join data if present will contain all informations
                if(!empty($row['join_table'])){
                    $rel['join_table'] = $row['join_table'];
                    $rel['join_key_lhs'] = $row['join_key_lhs'];
                    $rel['join_key_rhs'] = $row['join_key_rhs'];
                }

                // parse relationship name
                $row['relationship_name'] = str_replace('{lhs_table}', $row['lhs_table'], $row['relationship_name']);
                if(!empty($row['rhs_table'])){
                    $row['relationship_name'] = str_replace('{rhs_table}', $row['rhs_table'], $row['relationship_name']);
                }
                if(!empty($row['join_dictionary_name'])) {
                    $row['relationship_name'] = str_replace('{join_dictionary_name}', $row['join_dictionary_name'], $row['relationship_name']);
                }
                $rel['relationship_name'] = $row['relationship_name'];

                // additional columns
                if(!empty($row['relationship_role_column'])){
                    $rel['relationship_role_column'] = $row['relationship_role_column'];
                }
                if(!empty($row['relationship_role_column_value'])){
                    $rel['relationship_role_column_value'] = $row['relationship_role_column_value'];
                }

                // rhs is a template. In that case we may have multiple relations
                if(empty($row['rhs_module'])){
                    // retrieve data based on template dictionary
                    $rhs_rels = self::getRHSByTemplateAllocations($row['rhs_sysdictionarydefinition_id'], $row['rhs_sysdictionaryitem_id'] );
                    foreach($rhs_rels as $rhs_rel){
                        // create multiple entries for relationships. typically assigned_user relationships
                        $multi_rel = [
                            'id' => $rel['id'],
                            'relationship_name' => str_replace('{rhs_table}', $rhs_rel['rhs_table'], $row['relationship_name']),
                            'relationship_type' => $rel['relationship_type'],
                            'lhs_module' => $rel['lhs_module'],
                            'lhs_table' => $rel['lhs_table'],
                            'lhs_key' => $rel['lhs_key'],
                            'lhs_sysdictionarydefinition_id' => $rel['lhs_sysdictionarydefinition_id'],
                            'rhs_module' =>  $rhs_rel['rhs_module'],
                            'rhs_table' =>  $rhs_rel['rhs_table'],
                            'rhs_key' =>  $rhs_rel['rhs_key'],
                            'rhs_sysdictionarydefinition_id' =>  $rhs_rel['rhs_sysdictionarydefinition_id'],
                            'rhs_relatename' => $rel['rhs_relatename']
                        ];
                        if(!empty($multi_rel['rhs_table'])){
                            $multi_rel['lhs_linkname'] = str_replace('{rhs_table}', $multi_rel['rhs_table'], $rel['lhs_linkname']);
                        }
                        if(!empty($multi_rel['lhs_table'])){
                            $multi_rel['rhs_linkname'] = str_replace('{lhs_table}', $multi_rel['lhs_table'], $rel['rhs_linkname']);
                        }

                        $relationships[$multi_rel['relationship_name']] = $multi_rel;
                    }
                } else{
                    $relationships[$rel['relationship_name']] = $rel;
                }
            }
        }
        
        return $relationships;
    }

    /**
     * get modules, tables and keys for which template dictinary applies
     * Used to match relationships
     * @param $sysdictionarydefinition_id
     * @param $sysdictionaryitem_id
     * @return array
     */
    static function getRHSByTemplateAllocations($sysdictionarydefinition_id, $sysdictionaryitem_id){
        $rhs_rels = [];
        $q = "SELECT sysmod.module rhs_module, sysd.tablename rhs_table, sysditemkeys.name rhs_key, sysd.id rhs_sysdictionarydefinition_id
FROM (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) sysd 
LEFT JOIN (select * from sysmodules UNION select * from syscustommodules) sysmod on sysmod.sysdictionarydefinition_id = sysd.id
LEFT JOIN (select * from sysdictionaryitems union select * from sysdictionaryitems)  sysditems ON sysditems.sysdictionarydefinition_id = sysd.id
LEFT JOIN (select * from sysdictionaryitems union select * from sysdictionaryitems)  sysditemkeys ON sysditemkeys.id = '{$sysdictionaryitem_id}'
where sysditems.sysdictionary_ref_id = '{$sysdictionarydefinition_id}'  AND sysd.deleted=0";

//        die($q);
        if($res = $GLOBALS['db']->query($q)) {
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                $rhs_rels[] = $row;
            }
        }
        return $rhs_rels;
    }

    static function loadLinksForDictionary(&$dictionaryDef, $contextModule) {
//        echo '<pre>'.print_r($dictionaryDef, true);
        foreach($GLOBALS['relationships'] as $rel_name => $rel_def){

            if($rel_def['lhs_module'] == $contextModule){
                // set left link
                $dictionaryDef['fields'][$rel_def['lhs_linkname']] = [
                    'name' => $rel_def['lhs_linkname'],
                    'vname' => '??lhs',
                    'source' => 'non-db',
                    'relationship' => $rel_name,
                    'module' => $rel_def['rhs_module'],
                    'default' => '??'
                ];
            }
            if($rel_def['rhs_module'] == $contextModule){
                // set right link
                $dictionaryDef['fields'][$rel_def['rhs_linkname']] = [
                    'name' => $rel_def['rhs_linkname'],
                    'vname' => '??rhs',
                    'source' => 'non-db',
                    'relationship' => $rel_name,
                    'module' => $rel_def['lhs_module'],
                    'default' => '??'
                ];
                // set relate
                if(!empty($rel_def['rhs_relatename'])){
                    $dictionaryDef['fields'][$rel_def['rhs_relatename']] = [
                        'name' => $rel_def['rhs_relatename'],
                        'vname' => '??rhs_relatename',
                        'source' => 'non-db',
                        'link' => $rel_def['rhs_linkname'],
                        'module' => $rel_def['lhs_module'],
                        'id_name' => $rel_def['rhs_key']
                    ];
                    $relateFieldData = self::loadRelationshipRelateFields($rel_def['id']);
                    if(!empty($relateFieldData)){
                        $dictionaryDef['fields'][$rel_def['rhs_relatename']]['rname'] = $relateFieldData[0]['fieldname'];
                        if(count($relateFieldData) > 1) {
                            foreach ($relateFieldData as $idx => $relateField) {
                                $dictionaryDef['fields'][$rel_def['rhs_relatename']]['db_concat_fields'][] = $relateField['fieldname'];
                            }
                        }
                    }
                }
            }
        }
    }




//    /**
//     * Load relationships from table for buildRelationshipCache
//     * @param $def
//     * @param $dictionaryname
//     */
//    static function loadDictionaryRelationships(&$dictionaryDef, $dictionary_id, $contextmodule){
//        if(isset($GLOBALS['sugar_config']['systemvardefs']['dictionary']) && $GLOBALS['sugar_config']['systemvardefs']['dictionary']) {
//            $q = "SELECT rel.*, join_dicts.tablename join_table,
//lhs_sysm.module lhs_module, lhs_sysm.bean lhs_bean, lhs_dicts.tablename lhs_table, lhs_dicts.sysdictionary_type lhs_dictionary_type, lhs_sysdictitems.name lhs_key, lhs_sysm.module_label lhs_module_label, join_lhs_sysdictitems.name join_key_lhs,
//rhs_sysm.module rhs_module, rhs_sysm.bean rhs_bean, rhs_dicts.tablename rhs_table, rhs_dicts.sysdictionary_type rhs_dictionary_type, rhs_sysdictitems.name rhs_key, rhs_sysm.module_label rhs_module_label, join_rhs_sysdictitems.name join_key_rhs
//
//    FROM ( select * from sysdictionaryrelationships union select * from syscustomdictionaryrelationships) rel
//
//   LEFT JOIN (select * from sysmodules union select * from syscustommodules) lhs_sysm ON lhs_sysm.sysdictionarydefinition_id = rel.lhs_sysdictionarydefinition_id
//   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) lhs_sysdictitems ON lhs_sysdictitems.id = rel.lhs_sysdictionaryitem_id
//   LEFT JOIN (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) lhs_dicts ON lhs_dicts.id = rel.lhs_sysdictionarydefinition_id
//   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) join_lhs_sysdictitems ON join_lhs_sysdictitems.id = rel.join_lhs_sysdictionaryitem_id
//
//   LEFT JOIN (select * from sysmodules union select * from syscustommodules) rhs_sysm ON rhs_sysm.sysdictionarydefinition_id = rel.rhs_sysdictionarydefinition_id
//   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) rhs_sysdictitems ON rhs_sysdictitems.id = rel.rhs_sysdictionaryitem_id
//   LEFT JOIN (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) rhs_dicts ON rhs_dicts.id = rel.rhs_sysdictionarydefinition_id
//   LEFT JOIN (select * from sysdictionaryitems union select * from syscustomdictionaryitems) join_rhs_sysdictitems ON join_rhs_sysdictitems.id = rel.join_rhs_sysdictionaryitem_id
//
//      LEFT JOIN (select * from sysdictionarydefinitions union select * from sysdictionarydefinitions) join_dicts ON join_dicts.id = rel.join_sysdictionarydefinition_id
//
//   WHERE rel.deleted=0 AND (rel.rhs_sysdictionarydefinition_id = '{$dictionary_id}')
//	GROUP BY rel.id
//";
////if($contextmodule == 'Opportunities'){
////    echo '<pre>'.$q;
////}
//
//            if($res = $GLOBALS['db']->query($q)){
//                while($row = $GLOBALS['db']->fetchByAssoc($res)){
//
//                    switch($row['rhs_dictionary_type']){
//                        case 'template':
//                            $context_dictionary_data = self::getModuleDictionaryMainDataByModuleName($contextmodule);
//                            if($contextmodule == $context_dictionary_data['module']){
//                                // build relationship properties depending on relationship type
//                                $relationship_name = str_replace('{rhs_table}', $context_dictionary_data['tablename'], $row['relationship_name']);
////                                if(empty($row['rhs_key'])) {
////                                    $row['rhs_key'] = $row['rhs_sysdictionaryitem_name'];
////                                }
//                                $dictionaryDef['relationships'][$relationship_name] = [
//                                    'lhs_module' => $row['lhs_module'],
//                                    'lhs_table' => $row['lhs_table'],
//                                    'lhs_key' => $row['lhs_key'],
//                                    'rhs_module' => $contextmodule,
//                                    'rhs_table' => $context_dictionary_data['tablename'],
//                                    'rhs_key' => $row['rhs_key'],
//                                    'relationship_type' => $row['relationship_type']
//                                ];
//                                if(!empty($row['relationship_role_column'])){
//                                    $dictionaryDef['relationships'][$row['relationship_name']]['relationship_role_column'] = $row['relationship_role_column'];
//                                }
//                                if(!empty($row['relationship_role_column_value'])){
//                                    $dictionaryDef['relationships'][$row['relationship_name']]['relationship_role_column_value'] = $row['relationship_role_column_value'];
//                                }
//                                if(!empty($row['reverse'])){
//                                    $dictionaryDef['relationships'][$row['relationship_name']]['reverse'] = $row['reverse'];
//                                }
//
//                                if(!empty($row['join_sysdictionarydefinition_id'])){
//                                    $dictionaryDef['relationships'][$row['relationship_name']]['join_table'] = self::getTableNameByDictionaryId($row['join_sysdictionarydefinition_id']);
//                                    $dictionaryDef['relationships'][$row['relationship_name']]['join_key_lhs'] = self::getFieldNameByDictionaryItemId($row['join_lhs_sysdictionaryitem_id']);
//                                    $dictionaryDef['relationships'][$row['relationship_name']]['join_key_rhs'] = self::getFieldNameByDictionaryItemId($row['join_rhs_sysdictionaryitem_id']);
//                                }
//
//                                // add link definitions
//                                switch($row['relationship_type']){
//                                    case 'one-to-many':
//                                        // right side link
//                                        $link_name = $row['rhs_linkname'];
//                                        $dictionaryDef['fields'][$link_name] = [
//                                            'name' => $link_name,
//                                            'vname' => $row['lhs_module_label'],
//                                            'relationship' => $relationship_name,
//                                            'module' => $row['lhs_module'],
//                                            'bean_name' => $row['lhs_bean'],
//                                        ];
//                                        // right side relate field
//                                        $dictionaryDef['fields'][$row['rhs_relatename']] = [
//                                            'name' => $row['rhs_relatename'],
//                                            'vname' => '???',
//                                            'type' => 'relate',
//                                            'source' => 'non-db',
//                                            'id_name' => $row['rhs_key'],
//                                            'link' => $link_name,
//                                            'module' => $row['lhs_module'],
//                                            'default' => 0
//                                        ];
//                                        // enrich rname property
//                                        $relateFieldData = self::loadRelationshipRelateFields($row['id']);
//                                        if(!empty($relateFieldData)){
//                                            foreach($relateFieldData as $idx => $relateField){
//                                                $dictionaryDef['fields'][$row['rhs_relatename']]['db_concat_fields'][] = $relateField['fieldname'];
//                                            }
//                                        }
//
//                                        break;
//                                }
//
//                            }
//
//                            break;
//
//                        case 'module':
//
//                            break;
//                    }
//
//                }
//            }
//        }
//
//    }


    /**
     * @todo: check that group is no longer relevant property in UI
     * @deprecated
     * @param $row
     * @return mixed
     */
    static function parseGroupName($row){
        $groupname = $row['fieldgroup'];
        $groupname = str_replace("{sysdictionaryitems.name}", $row['itemname'], $groupname);
        $groupname = str_replace("{sysdomaindefinitions.name}", $row['domainname'], $groupname);
        return $groupname;
    }

    /**
     * @param $sysdictionary_id
     * @return mixed
     */
    public static function getTableNameByDictionaryId($sysdictionary_id){
        $q = "SELECT id, tablename FROM (select id, tablename from sysdictionarydefinitions union select id, tablename from syscustomdictionarydefinitions) sysd
            WHERE sysd.id = '{$sysdictionary_id}' ";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                return $row['tablename'];
            }
        }
        return false;
    }

    /**
     * @param $sysdictionaryitem_id
     * @return mixed
     */
    public static function getFieldNameByDictionaryItemId($sysdictionaryitem_id){
        $q = "SELECT id, name FROM (select id, name from sysdictionaryitems union select id, name from syscustomdictionaryitems) sysdi
            WHERE sysdi.id = '{$sysdictionaryitem_id}' ";
        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                return $row['name'];
            }
        }
        return false;
    }

    /**
     * @param $relationship_id
     * @return array
     */
    public static function loadRelationshipRelateFields($relationship_id){
        $relateFields = [];
        $q = "select sysditems.name fieldname, sysditems.label fieldlabel, sysdomfields.fieldtype, sysdomfields.len fieldlen
        FROM (select * from sysdictionaryitems UNION select * from syscustomdictionaryitems) sysditems
        INNER JOIN (select * from sysdomaindefinitions UNION select * from syscustomdomaindefinitions) sysdomns ON sysdomns.id = sysditems.sysdomaindefinition_id
        INNER JOIN (select * from sysdomainfields UNION select * from syscustomdomainfields) sysdomfields ON sysdomfields.sysdomaindefinition_id = sysdomns.id
        INNER JOIN (select * from sysdictionaryrelationshiprelatefields UNION select * from syscustomdictionaryrelationshiprelatefields) sysdrelfields ON sysdrelfields.sysdictionaryitem_id = sysditems.id
        WHERE sysdrelfields.sysdictionaryrelationship_id = '{$relationship_id}'
        ORDER BY sysdrelfields.sequence ASC";

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                $relateFields[] = [
                    'fieldname' => $row['fieldname'],
                    'fieldtype' => $row['fieldtype'],
                    'fieldlen' => $row['fieldlen'],
                    'fieldlabel' => $row['fieldlabel'],
                ];
            }
        }
        return $relateFields;
    }


    /**
     * retrieves validations for a domain
     * @param $sysdomaindefinition_id
     * @return array
     */
    static function getSysDomainFieldValidationBySysDomainId($sysdomaindefinition_id){
        $q = "SELECT sysdofv.*
            FROM sysdomainfieldvalidations sysdofv 
            INNER JOIN sysdomainfields sysdof ON sysdof.sysdomainfieldvalidation_id = sysdofv.id
            INNER JOIN sysdomaindefinitions sysdo ON sysdo.id = sysdof.sysdomaindefinition_id
            WHERE sysdo.id='".$sysdomaindefinition_id."'";

        if($res = $GLOBALS['db']->query($q)){
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
                return $row;
            }
        }
        return [];
    }

    /**
     * create fieldname
     * parse dynamic field definition
     * @param $row
     * @return mixed
     */
    static function parseFieldName(&$row){
        $technicalname = $row['technicalname'];
        $technicalname = str_replace("{sysdictionaryitems.name}", $row['itemname'], $technicalname);
        $technicalname = str_replace("{sysdomaindefinitions.name}", $row['domainname'], $technicalname);
        $technicalname = str_replace("{sysdictionarydefinitions.tablename}", $row['tablename'], $technicalname);

        if(isset($row['id_name'])){
            $row['id_name'] = str_replace("{sysdictionaryitems.name}", $row['itemname'], $row['id_name']);
        }
//        if(isset($row['link'])){
//            $row['link'] = str_replace("{sysdictionarydefinitions.tablename}", $row['tablename'], $row['link']);
//            $row['link'] = str_replace("{sysdictionaryitems.name}", $row['itemname'], $row['link']);
//        }
        return $technicalname;
    }



    static function getSysDomainFieldValidationByName($name){
        $q = "SELECT sysdov.id FROM sysdomainfieldvalidations sysdov 
                        INNER JOIN sysdomainfieldvalidationvalues sysdovv ON sysdov.id = sysdovv.sysdomainfieldvalidation_id
                        WHERE sysdov.name ='{$name}'";

        $res = $GLOBALS['db']->query($q);
        $row = $GLOBALS['db']->fetchByAssoc($res);
        return $row;
    }

    /**
     * create sysdomain with sysdomainfield and relate for validation
     * triggered by specific field
     * @param $field vardef
     */
    static function createSysDomainForValidation($field, $sysdomainfieldvalidation_id){
        $qi = [];
        $sysdomaindefinition_id = create_guid();
        $qi[] = "INSERT INTO sysdomaindefinitions (id, name, fieldtype, fieldlen) VALUES('{$sysdomaindefinition_id}', '{$field['options']}', '{$field['type']}', '{$field['len']}');";
        $qi[] = "INSERT INTO sysdomainfields (id, name, dbtype, fieldlen, sysdomaindefinition_id, sysdomainfieldvalidation_id, fieldtype, fieldcomment) VALUES(uuid(), '{sysdictionaryitems.name}', '".($field['dbType'] ? $field['dbType'] : 'varchar')."', '{$field['len']}', '{$sysdomaindefinition_id}', '{$sysdomainfieldvalidation_id}', '{$field['type']}', '".$GLOBALS['db']->quote($field['description'])."');";
        foreach($qi as $q){
            $GLOBALS['db']->query($q);
        }
        return $sysdomaindefinition_id;
    }

    /**
     * returns the id of sysdomain correspondig to vardef['type']
     * Query will deviate when handling enums
     * @param $field
     * @return mixed
     */
    static function getSysDomainByFieldType ($field){
        //handle dbType / dbtype
        if(isset($field['dbtype'])){
            $field['dbType'] = $field['dbtype'];
        }

        // handle enums separately
        switch($field['type']){
            case 'radio':
            case 'enum':
            case 'multienum':
                //@todo: catch error when options is empty
                $q = "SELECT * FROM sysdomaindefinitions WHERE fieldtype='{$field['type']}' AND name='{$field['options']}'";
                $res = $GLOBALS['db']->query($q);
                $row = $GLOBALS['db']->fetchByAssoc($res);

                if(empty($row)){
                    // we might have to create the sysdomain. Check if we have a validation entry
                    $row = self::getSysDomainFieldValidationByName($field['options']);
                    if(!empty($row)){
                        return self::createSysDomainForValidation($field, $row['id']);
                    }
                }
                break;
            case 'datetimecombo':
                $field['type'] = 'datetime';
                $q = "SELECT * FROM sysdomaindefinitions WHERE fieldtype='{$field['type']}'";
                $res = $GLOBALS['db']->query($q);
                $row = $GLOBALS['db']->fetchByAssoc($res);
                breaK;
            case 'url':
            case 'user_name':
            case 'name':
            case 'phone':
            case 'companies':
            case 'mailbox':
            case 'mailboxtransport':
            case 'email':
            case 'language':
            case 'file':
            case 'actionset':
                $field['type'] = 'varchar';
                $q = "SELECT * FROM sysdomaindefinitions WHERE fieldtype='{$field['type']}'";
                $res = $GLOBALS['db']->query($q);
                $row = $GLOBALS['db']->fetchByAssoc($res);
                break;
            case 'tags':
            case 'json':
                $field['type'] = 'text';
                $q = "SELECT * FROM sysdomaindefinitions WHERE fieldtype='{$field['type']}'";
                $res = $GLOBALS['db']->query($q);
                $row = $GLOBALS['db']->fetchByAssoc($res);
                break;
            default:
                $q = "SELECT * FROM sysdomaindefinitions WHERE fieldtype='{$field['type']}'";
                $res = $GLOBALS['db']->query($q);
                $row = $GLOBALS['db']->fetchByAssoc($res);
        }

//        $res = $GLOBALS['db']->query($q);
//        $row = $GLOBALS['db']->fetchByAssoc($res);

        //@todo: catch error when nothing was found

        return $row['id'];
    }

    /**
     * returns relationship definition
     * @param $name
     * @return mixed
     */
    static function getRelationshipByName($name){
        $row = [];
        $q = "SELECT * FROM sysdictionaryrelationships WHERE relationship_name = '{$name}' ";
        $q.= " UNION SELECT * FROM syscustomdictionaryrelationships WHERE relationship_name = '{$name}' ";
        $q.= " WHERE relationship_name = '{$name}' ";
        $q.= " GROUP BY relationship_name ";
        if($res = $GLOBALS['db']->query($q)){
            $row = $GLOBALS['db']->fetchByAssoc($res);
        }
        return $row;
    }




    /**
     * get full structure
     * @return mixed
     */
    static function getSysDomains (){
        $sysdomaindefinitions = [];
        $sysdomain_display = "";

        $q = "SELECT sysdo.name sysdomain_name, sysdof.*
    FROM sysdomaindefinitions sysdo
    INNER JOIN sysdomainfields sysdof ON sysdof.sysdomaindefinition_id = sysdo.id
ORDER BY  sysdo.name
    ";
        if($res = $GLOBALS['db']->query($q)){
            $sysdomain_display ="<table>";
            while($row = $GLOBALS['db']->fetchByAssoc($res)){
//                if(!isset($cols)){
//                    $cols = array_keys($row);
//                }
//                if(!in_array($row['sysdomain_name'], $sysdomaindefinitions)){
//                    $sysdomaindefinitions[] = $row['sysdomain_name'];
//                    $display = "<h1>".$row['sysdomain_name']."</h1>";
//                    $display.= "<table><tr><td>";
//                    $display.= implode("</td> <td>", $cols);
//                    $display.= "</td></tr>";
//                }
                $display = "<tr><td>".implode("</td> <td>", array_values($row))."</td></tr>";
                $sysdomain_display.= $display;
            }
            $sysdomain_display.="</table>";
        }
        return $sysdomain_display;
    }


    /**
     * read validations and create app_list_strings doms
     */
    static function loadDictionaryValidations(){
        global $db;

        if($_SESSION['systemvardefs']['domains']){
            return $_SESSION['systemvardefs']['domains'];
        }

        $retArray = [];
        // core values
        $coreEnums = $db->query("SELECT id, name FROM sysdomainfieldvalidations WHERE validation_type = 'enum' AND deleted = 0");
        while($coreEnum = $db->fetchByAssoc($coreEnums)){
            $retArray[$coreEnum['name']]['name'] = $coreEnum['name'];
            $retArray[$coreEnum['name']]['values'] = [];
            $enumValues = $db->query("SELECT minvalue, sequence, label FROM sysdomainfieldvalidationvalues WHERE sysdomainfieldvalidation_id = '{$coreEnum['id']}' AND status = 'a' AND deleted = 0");
            while($enumValue = $db->fetchByAssoc($enumValues)){
                $retArray[$coreEnum['name']]['values'][$enumValue['minvalue']] = [
                    'minvalue' => $enumValue['minvalue'],
                    'label' => $enumValue['label'],
                    'sequence' => $enumValue['sequence']
                ];
            }

            // load custom enum values
            $cenumValues = $db->query("SELECT minvalue, sequence, label FROM syscustomdomainfieldvalidationvalues WHERE sysdomainfieldvalidation_id = '{$coreEnum['id']}' AND status = 'a' AND deleted = 0");
            while($cenumValue = $db->fetchByAssoc($cenumValues)){
                $retArray[$coreEnum['name']]['values'][$cenumValue['minvalue']] = [
                    'minvalue' => $cenumValue['minvalue'],
                    'label' => $cenumValue['label'],
                    'sequence' => $cenumValue['sequence']
                ];
            }
        }

        // custom values
        $coreEnums = $db->query("SELECT id, name FROM syscustomdomainfieldvalidations WHERE validation_type = 'enum' AND deleted = 0");
        while($coreEnum = $db->fetchByAssoc($coreEnums)){
            $retArray[$coreEnum['name']]['name'] = $coreEnum['name'];
            $retArray[$coreEnum['name']]['values'] = [];

            // load custom enum values
            $cenumValues = $db->query("SELECT minvalue, sequence, label FROM syscustomdomainfieldvalidationvalues WHERE sysdomainfieldvalidation_id = '{$coreEnum['id']}' AND status = 'a' AND deleted = 0");
            while($cenumValue = $db->fetchByAssoc($cenumValues)){
                $retArray[$coreEnum['name']]['values'][$cenumValue['minvalue']] = [
                    'minvalue' => $cenumValue['minvalue'],
                    'label' => $cenumValue['label'],
                    'sequence' => $cenumValue['sequence']
                ];
            }
        }

        // save to the session
        $_SESSION['systemvardefs']['domains'] = $retArray;

        return $retArray;
    }



    static function getLanguages($sysonly = true){
        $languages = [];
        $results = $GLOBALS['db']->query("SELECT language_code FROM syslangs " . ($sysonly ? "WHERE system_language = 1" : ""). " ORDER BY sort_sequence, language_name");
        while($row = $GLOBALS['db']->fetchByAssoc($results)){
            $languages[] = $row['language_code'];
        }
        return $languages;
    }

    /**
     * build an array containing doms for each language
     * @param $language
     * @return array
     */
    static function createDictionaryValidationDoms($language = null){
        if(empty($language)){
            $language = $GLOBALS['current_language'];
        }
        if (!class_exists('LanguageManager')) require_once 'include/SugarObjects/LanguageManager.php';
        $sys_app_list_strings = [];
        $validations = self::loadDictionaryValidations();
        $syslanguagelabels[$language] = \LanguageManager::loadDatabaseLanguage($language);
        foreach($validations as $dom => $definition){

            // re-organize and add translation
            foreach($definition['values'] as $minvalue => $def){
                $translation = (!empty($syslanguagelabels[$language][$def['label']]['default']) ? $syslanguagelabels[$language][$def['label']]['default'] : $minvalue);
                $sys_app_list_strings[$dom][$language]['values'][$minvalue]['minvalue'] = $minvalue;
                $sys_app_list_strings[$dom][$language]['values'][$minvalue]['translation'] = $translation;
                $sys_app_list_strings[$dom][$language]['values'][$minvalue]['sequence'] = $def['sequence'];
            }

            // sort by the sequence
            $arrmap = array_map(function($element) {
                return $element['sequence'];
            }, $sys_app_list_strings[$dom][$language]['values']);
            array_multisort($arrmap, ($definition['sort_flag'] == 'desc' ? SORT_DESC : SORT_ASC), $sys_app_list_strings[$dom][$language]['values']);
        }

        return $sys_app_list_strings;
    }


    /**
     * Given a module, search all of the specified locations, and any others as specified
     * in order to refresh the cache file
     *
     * @param string $module the given module we want to load the vardefs for
     * @param string $object the given object we wish to load the vardefs for
     * @param array $additional_search_paths an array which allows a consumer to pass in additional vardef locations to search
     */
    static function refreshVardefs($module, $object, $additional_search_paths = null, $cacheCustom = true, $params = array()){
        self::saveCache($module, $object);
    }

    /**
     * Save the dictionary object to the cache
     * @param string $module the name of the module
     * @param string $object the name of the object
     */
    static function saveCache($module){
        $object = $GLOBALS['beanList'][$module];
        if (empty($GLOBALS['dictionary'][$object]))
            $object = \BeanFactory::getObjectName($module);
        $file = create_cache_directory('modules/' . $module . '/' . $object . 'vardefs.php');

        $out="<?php \n \$GLOBALS[\"dictionary\"][\"". $object . "\"]=" . var_export($GLOBALS['dictionary'][$object], true) .";";
        sugar_file_put_contents_atomic($file, $out);
        if ( sugar_is_file($file) && is_readable($file)) {
            include($file);
        }

        // put the item in the sugar cache.
        $key = "VardefManager.$module.$object";
        //Sometimes bad definitions can get in from left over extensions or file system lag(caching). We need to clean those.
        $data = self::cleanVardefs($GLOBALS['dictionary'][$object]);
        sugar_cache_put($key,$data);
    }

    /**
     * Save the dictionary object to the cache
     * @param string $module the name of the module
     * @param string $object the name of the object
     */
    static function saveCacheRelationships($data){
        $cacheFile = sugar_cached("Relationships/relationships.cache.php");
        //Save it out
        sugar_mkdir(dirname($cacheFile), null, true);
        $out = "<?php \n \$relationships = " . var_export($data, true) . ";";
        sugar_file_put_contents_atomic($cacheFile, $out);

    }

    /**
     * Remove invalid field definitions
     * @static
     * @param Array $fieldDefs
     * @return  Array
     */
    static function cleanVardefs($fieldDefs)
    {
        foreach($fieldDefs as $field => $defs)
        {
            if (empty($def['name']) || empty($def['type']))
            {
                unset($fieldDefs[$field]);
            }
        }

        return $fieldDefs;
    }
}
