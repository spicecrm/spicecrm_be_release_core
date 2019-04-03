<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIRepositoryController
{
    static function getModuleRepository()
    {
        global $db;
        
        $retArray = array();
        $modules = $db->query("SELECT * FROM sysuimodulerepository UNION SELECT * FROM sysuicustommodulerepository");
        while ($module = $db->fetchByAssoc($modules)) {
            $retArray[$module['id']] = array(
                'id' => $module['id'],
                'path' => $module['path'],
                'module' => $module['module']
            );
        }

        return $retArray;
    }

    static function getComponents()
    {
        global $db;
        
        $retArray = array();
        $components = $db->query("SELECT * FROM sysuiobjectrepository UNION SELECT * FROM sysuicustomobjectrepository");
        while ($component = $db->fetchByAssoc($components)) {
            $retArray[$component['object']] = array(
                'path' => $component['path'],
                'component' => $component['component'],
                'module' => $component['module'],
                'componentconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($component['componentconfig'])), true) ?: array()
            );
        }

        return $retArray;
    }

    static function getComponentDefaultConfigs()
    {
        global $db;
        
        $retArray = array();
        $componentconfigs = $db->query("SELECT * FROM sysuicomponentdefaultconf");
        while ($componentconfig = $db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new \stdClass();
        }

        $componentconfigs = $db->query("SELECT * FROM sysuicustomcomponentdefaultconf");
        while ($componentconfig = $db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new \stdClass();
        }

        return $retArray;
    }

    static function getComponentModuleConfigs()
    {
        global $db;
        
        $retArray = array();
        $componentconfigs = $db->query("SELECT * FROM sysuicomponentmoduleconf");
        while ($componentconfig = $db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['module']][$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new \stdClass();
        }

        $componentconfigs = $db->query("SELECT * FROM sysuicustomcomponentmoduleconf");
        while ($componentconfig = $db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['module']][$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new \stdClass();
        }

        return $retArray;
    }

    /**
     * retirves the libs defined for the laoder
     * 
     * @return array
     */
    static function getLibraries()
    {
        global $db;
        
        $return = [];
        $sql = "SELECT * FROM (SELECT * FROM sysuilibs UNION SELECT * FROM sysuicustomlibs) libs ORDER BY libs.rank ASC";
        $res = $db->query($sql);
        while($row = $db->fetchByAssoc($res, false))
        {
            $return[$row['name']][] = ['loaded' => false, 'src' => $row['src']];
        }

        return $return;
    }

}