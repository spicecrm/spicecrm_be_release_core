<?php

class EvalancheHooks
{
    public function handlerHooks(&$bean, $event, $arguments)
    {
        switch ($event) {
            case 'after_save':
                if ($bean->gdpr_marketing_agreement != 'g') {
                    $evalanche = new \SpiceCRM\includes\Evalanche\Evalanche();
                    $evalanche->deleteFromEvalanche($bean->id, $bean->module_name);
                } else {
                    $evalanche = new \SpiceCRM\includes\Evalanche\Evalanche();
                    $evalanche->createProfileFromBean($bean->id, $bean->module_name);
                }
                break;
            case 'before_delete':
                $evalanche = new \SpiceCRM\includes\Evalanche\Evalanche();
                $evalanche->deleteFromEvalanche($bean->id, $bean->module_name);
                break;
        }

    }

}
