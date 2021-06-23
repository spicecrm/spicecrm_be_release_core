<?php
namespace SpiceCRM\modules\ServiceTickets\api\controllers;

use SpiceCRM\data\BeanFactory;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use KRESTModuleHandler;//todo-uebelmar clarify
use SpiceCRM\KREST\handlers\ModuleHandler;
use SpiceCRM\includes\authentication\AuthenticationController;
use SpiceCRM\modules\SpiceACL\SpiceACL;
use Slim\Psr7\Request as Request;
use SpiceCRM\includes\SpiceSlim\SpiceResponse as Response;

class ServiceTicketsController
{
    public function openInMyQueues(Request $req, Response $res, array $args): Response {
        $current_user = AuthenticationController::getInstance()->getCurrentUser();

        $retArray = [
            'tickets'    => [],
            'totalcount' => 0,
        ];

        $KRESTModuleHandler = new ModuleHandler();

        $serviceTicket = BeanFactory::getBean('ServiceTickets');
        $tickets = $serviceTicket->getUserQueuesTickets();

        $retArray['totalcount'] = $tickets['row_count'];

        foreach ($tickets['list'] as $ticket) {
            $retArray['tickets'][] = $KRESTModuleHandler->mapBeanToArray('ServiceTickets', $ticket);
        }

        return $res->withJson($retArray);
    }

    public function myOpenItems(Request $req, Response $res, array $args): Response {
        $retArray = [
            'tickets'    => [],
            'totalcount' => 0,
        ];

        $KRESTModuleHandler = new ModuleHandler();

        $serviceTicket = BeanFactory::getBean('ServiceTickets');
        $tickets = $serviceTicket->getUserOpenTickets();

        $retArray['totalcount'] = $tickets['row_count'];

        foreach ($tickets['list'] as $ticket) {
            $retArray['tickets'][] = $KRESTModuleHandler->mapBeanToArray('ServiceTickets', $ticket);
        }

        return $res->withJson($retArray);
    }

    public function prolong(Request $req, Response $res, array $args): Response {
        $current_user = AuthenticationController::getInstance()->getCurrentUser();

        if (!SpiceACL::getInstance()->checkAccess('ServiceTickets', 'edit', true)){
            throw (new ForbiddenException("Forbidden to edit in module ServiceTickets."))
                ->setErrorCode('noModuleEdit');
        }

        $ticket = BeanFactory::getBean('ServiceTickets', $args['beanId']);
        if (!$ticket || !$ticket->ACLAccess('edit')) {
            throw (new ForbiddenException('Forbidden to edit record.'))->setErrorCode('noRecordEdit');
        }

        $postBody = $req->getParsedBody();

        // update the ticket
        $ticket->prolonged_until = $postBody['prolonged_until'];
        $ticket->save();

        // save the prolongation
        $prolongation = BeanFactory::getBean('ServiceTicketProlongations');
        $prolongation->prolonged_until = $postBody['prolonged_until'];
        $prolongation->name = $current_user->user_name . '/'. $postBody['prolonged_until'];
        $prolongation->serviceticket_id = $ticket->id;
        $prolongation->description = $postBody['prolongation_reason'];
        $prolongation->assigned_user_id = $current_user->id;

        $prolongation->save();

        return $res->withJson(['success' => true]);
    }

    /**
     * tries to load data on the parent bean that is relevant for the creation of the ticket
     *
     * ServiceLocations & ServiceEquipment are laoded
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function discoverparent(Request $req, Response $res, array $args): Response {
        $locationsArray = [];

        // load the parent bean
        $parent = BeanFactory::getBean($args['parentType'], $args['parentId']);

        // check if we have a link that points to the servicelocations
        foreach($parent->field_defs as $fieldName => $fieldData){
            if($fieldData['type'] == 'link' && $fieldData['module'] == 'ServiceLocations'){
                $parent->load_relationship($fieldName);
                $serviceLocations = $parent->get_linked_beans($fieldName, 'ServiceLocation');
                foreach($serviceLocations as $serviceLocation){
                    $location = [
                        'id' => $serviceLocation->id,
                        'name' => $serviceLocation->name,
                        'serviceequipments' => []
                    ];
                    $serviceEquipments = $serviceLocation->get_linked_beans('serviceequipments', 'ServiceEquipment');
                    foreach ($serviceEquipments as $serviceEquipment){
                        $location['serviceequipments'][] = [
                            'id' => $serviceEquipment->id,
                            'name' => $serviceEquipment->name,
                        ];
                    }
                    $locationsArray[] = $location;
                }
                break;
            }
        }

        return $res->withJson(['servicelocations' => $locationsArray]);
    }
}
