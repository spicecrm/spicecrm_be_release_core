<?php

namespace SpiceCRM\modules\Tasks\filterfunctions;

class TasksFilters{
    function OpenAssignedTasks(){
        global $db, $current_user;

        $ids = [];
        $idsObj = $db->query("SELECT t.id FROM tasks t, tasks_users tu WHERE t.id = tu.task_id AND t.deleted = 0 AND tu.deleted = 0 AND tu.user_id='{$current_user->id}' AND t.status NOT IN ('Completed', 'Deferred')");
        while($id = $db->fetchByAssoc($idsObj)){
            $ids[] = $id['id'];
        }
        return $ids;
    }
}