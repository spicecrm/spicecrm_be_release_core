<?php
require_once('modules/ProjectWBSs/ProjectWBS.php');

$app->group('/ProjectWBSsHierarchy/{projectid}', function() use ($app) {
    $app->get('', function($req, $res, $args) use ($app) {
        global $db;
        $hierarchy = array();

        $seed = BeanFactory::getBean('Projects', $args['projectid']);
        $optional_where = "project_id = '".$args['projectid']."'";

        $memberProjectWBSs = $seed->get_linked_beans('projectwbss', 'ProjectWBS', array(), 0,-1,0, $optional_where);

        foreach ($memberProjectWBSs as $memberProjectWBS) {
            $q = "SELECT count(id) membercount FROM projectwbss 
            WHERE parent_id = '".$memberProjectWBS->id."' AND deleted = 0";
            $memberCount = $db->fetchByAssoc($db->query($q));
            $hierarchy[] = array(
                'id' => $memberProjectWBS->id,
                'project_id' => $memberProjectWBS->project_id,
                'parent_id' =>  $memberProjectWBS->parent_id,
                'summary_text' => $memberProjectWBS->get_summary_text(),
                'member_count' => $memberCount['membercount'],
                'planned_effort' => $memberProjectWBS->planned_effort
            );
        }

        echo json_encode($hierarchy);
    });
    $app->get('/{addfields}', function($req, $res, $args) use ($app) {
        global $db;
        //file_put_contents("sugarcrm.log", print_r("p+id+addfields", true)."\n", FILE_APPEND);

        $hierarchy = array();

        $args['addfields'] = json_decode(html_entity_decode($args['addfields']));
        $seed = BeanFactory::getBean('Projects', $args['projectid']);
        $seed->load_relationship('projectwbss');
        $optional_where = "project_id='".$args['projectid']."'";

        $memberProjectWBSs = $seed->get_linked_beans('projectwbss', 'ProjectWBS', array(), 0,-1,0, $optional_where);

        foreach ($memberProjectWBSs as $memberProjectWBS) {
			$q = "SELECT count(id) membercount FROM projectwbss 
            WHERE parent_id = '".$memberProjectWBS->id."' AND deleted = 0";
            $memberCount = $db->fetchByAssoc($db->query($q));
            $addData = array();
            foreach($args['addfields'] as $addfield)
                $addData[$addfield] = $memberProjectWBS->$addfield;

            $aclActions = ['list', 'detail', 'edit', 'delete', 'export'];
            foreach ($aclActions as $aclAction) {
                $addData['acl'][$aclAction] = $memberProjectWBS->ACLAccess($aclAction);
            }

            $hierarchy[] = array(
                'id' => $memberProjectWBS->id,
                'project_id' => $memberProjectWBS->project_id,
                'parent_id' =>  $memberProjectWBS->parent_id,
                'summary_text' => $memberProjectWBS->get_summary_text(),
                'member_count' => $memberCount['membercount'],
                'data' => $addData
            );
        }
        //file_put_contents("sugarcrm.log", print_r($hierarchy, true)."\n", FILE_APPEND);

        echo json_encode($hierarchy);
    });
});
