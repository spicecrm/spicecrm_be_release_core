<?php

$app->group('/dashboards', function () use ($app) {

    $app->get('', function () use ($app) {
        global $db;
        $dashBoards = array();
        $dashBoardsObj = $db->query("SELECT * FROM dashboards");
        while ($dashBoard = $db->fetchByAssoc($dashBoardsObj))
            $dashBoards[] = $dashBoard;
        echo json_encode($dashBoards);
    });
    $app->get('/dashlets', function () use ($app) {
        global $db;
        $dashBoardDashlets = array();
        $dashBoardDashletsObj = $db->query("SELECT * FROM sysuidashboarddashlets UNION SELECT * FROM sysuicustomdashboarddashlets");
        while ($dashBoardDashlet = $db->fetchByAssoc($dashBoardDashletsObj))
            $dashBoardDashlets[] = $dashBoardDashlet;
        echo json_encode($dashBoardDashlets);
    });
    $app->get('/{id}', function($req, $res, $args) use ($app) {
        global $db;
        $dashBoardBean = BeanFactory::getBean('Dashboards', $args['id']);
        $dashBoardBean = $dashBoardBean->retrieve($args['id']);
        echo $dashBoardBean->components;
    });
    $app->post('/{id}', function($req, $res, $args) use ($app) {
        global $db;
        $postbodyitems = $body = $req->getParsedBody();
        //$postParams = $_GET;
        //$postbodyitems = json_decode($postBody, true);

        $status = true;
        // todo: check if this is right to delete and reinsert all records .. might be nices to check what exists and update ...
        $db->query("DELETE FROM dashboardcomponents WHERE dashboard_id = '{$args['id']}'");
        foreach ($postbodyitems as $postbodyitem)
        {
            // $db->query("UPDATE sysuidashboardcomponents SET position='".json_encode($postbodyitem['position'])."', name='".$postbodyitem['name']."', component='".$postbodyitem['component']."' WHERE id = '".$postbodyitem['id']."'");
            $sql = "INSERT INTO dashboardcomponents (id, dashboard_id, name, component, componentconfig, position, dashlet_id) values('";
            $sql .= $postbodyitem['id'] . "', '{$args['id']}', '" . $postbodyitem['name'] . "', '" . $postbodyitem['component'];
            $sql .= "', '" . $db->quote(json_encode($postbodyitem['componentconfig']));
            $sql .= "', '" . $db->quote(json_encode($postbodyitem['position']));
            $sql .= "', '" . $postbodyitem['dashlet_id'] . "')";
            if( !$db->query($sql) ) throw ( new KREST\Exception( $db->last_error ))->setFatal(true);
        }
        echo json_encode(array('status' => $status));
    });
});