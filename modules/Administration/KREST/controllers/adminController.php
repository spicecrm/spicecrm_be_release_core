<?php

namespace SpiceCRM\modules\Administration\KREST\controllers;

class adminController
{
    public function systemstats($req, $res, $args)
    {
        global $db, $current_user, $sugar_config;

        $statsArray = [];

        if(!$current_user->is_admin) {
           throw new \SpiceCRM\KREST\ForbiddenException();
        }

        $stats = $db->query("SHOW TABLE STATUS");
        while($stat = $db->fetchByAssoc($stats)){

            $recordCount = $db->fetchByAssoc($db->query("SELECT count(*) records FROM {$stat['Name']}"));

            $statsArray['database'][] = [
                'name' => $stat['Name'],
                'records' => (int)$recordCount['records'],
                'size' => $stat['Data_length'] + $stat['Index_length']
            ];
        }

        // get the fts stats
        $ftsManager = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $statsArray['elastic'] = $ftsManager->getStats();

        $statsArray['uploadfiles'] = $this->getDirectorySize($sugar_config['upload_dir']);

        return $res->write(json_encode($statsArray));
    }

    function getDirectorySize($directory){
        $size = 0; $count = 0;
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file){
            $size+=$file->getSize();
            $count++;
        }
        return ['size' => $size, 'count' => $count];
    }
}
