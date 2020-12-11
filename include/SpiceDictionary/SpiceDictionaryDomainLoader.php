<?php


namespace SpiceCRM\includes\SpiceDictionary;

/**
 * a loader for the domains and domain validations
 *
 * Class SpiceDictionaryDomainLoader
 * @package SpiceCRM\includes\SpiceDictionary
 */
class SpiceDictionaryDomainLoader
{
    public function loadDomainValidations()
    {
        global $db;
        $validationsArray = [];
        $domainfields = $db->query("SELECT * FROM sysdomainfieldvalidations WHERE deleted = 0 AND status = 'a'");
        while($domainfield = $db->fetchByAssoc($domainfields)){
            $validationsArray[$domainfield['name']] = [
                'id' => $domainfield['id'],
                'validation_type' => $domainfield['validation_type'],
                'operator' => $domainfield['operator'],
                'validationvalues' => []
            ];
        }
        $domainfields = $db->query("SELECT * FROM syscustomdomainfieldvalidations WHERE deleted = 0 AND status = 'a'");
        while($domainfield = $db->fetchByAssoc($domainfields)){
            $validationsArray[$domainfield['name']] = [
                'id' => $domainfield['id'],
                'validation_type' => $domainfield['validation_type'],
                'operator' => $domainfield['operator'],
                'validationvalues' => []
            ];
        }

        // load the values
        foreach($validationsArray as $valname => $valdata){
            $domainvalues = $db->query("SELECT * FROM sysdomainfieldvalidationvalues WHERE sysdomainfieldvalidation_id = '{$valdata['id']}' AND deleted = 0 AND status = 'a'");
            while($domainvalue = $db->fetchByAssoc($domainvalues)){
                $validationsArray[$valname]['validationvalues'][] = [
                    'minvalue' => $domainvalue['minvalue'],
                    'maxvalue' => $domainvalue['maxvalue'],
                    'label' => $domainvalue['label'],
                    'sequence' => $domainvalue['sequence']
                ];
            }
        }

        return $validationsArray;

    }

}
