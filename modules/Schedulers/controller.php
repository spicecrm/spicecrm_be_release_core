<?php
require_once('include/MVC/Controller/SugarController.php');
require_once('include/utils.php');

class SchedulersController extends SugarController
{

    public function action_runtask()
    {
        $function = explode('::', $this->bean->job);
        if($function[0] == 'function'){
            require('modules/Schedulers/_AddJobsHere.php');
            call_user_func($function[1]);
        }
    }

}

