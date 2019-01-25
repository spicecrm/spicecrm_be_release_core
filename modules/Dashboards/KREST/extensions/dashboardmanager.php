<?php

$app->group('/dashboards', function () use ($app) {

    $app->group('/dashlets', function () use ($app) {
        $app->get('', function ($req, $res, $args) use ($app) {
            global $db;
            $dashBoardDashlets = array();
            $dashlets = "SELECT 'global' As `type`, dlts.* FROM sysuidashboarddashlets dlts UNION ";
            $dashlets .= "SELECT 'custom' As `type`, cdlts.* FROM sysuicustomdashboarddashlets cdlts";
            $dashlets = $db->query($dashlets);
            while ($dashBoardDashlet = $db->fetchByAssoc($dashlets))
                $dashBoardDashlets[] = $dashBoardDashlet;
            return $res->withJson($dashBoardDashlets);
        });
        $app->post('/{id}', function ($req, $res, $args) use ($app) {
            global $db;
            $columns = [];
            $values = [];
            $params = $body = $req->getParsedBody();
            $table = $params['type'] == 'global' ? 'sysuidashboarddashlets' : 'sysuicustomdashboarddashlets';
            foreach ($params as $column => $value) {
                if ($column != 'type') {
                    $columns[] = $db->quote($column);
                    $values[] = "'" . $db->quote($value) . "'";
                }
            }
            $columns = implode(',',$columns);
            $values = implode(',',$values);

            $isAdded = $db->query("REPLACE INTO $table ($columns) VALUES ($values)");
            return $res->withJson($isAdded);
        });
        $app->delete('/{id}', function ($req, $res, $args) use ($app) {
            global $db;
            $id = $db->quote($args['id']);
            $isDeleted = $db->query("DELETE FROM sysuidashboarddashlets WHERE id = '$id'");
            $isDeletedCustom = $db->query("DELETE FROM sysuicustomdashboarddashlets WHERE id = '$id'");
            return $res->withJson($isDeleted && $isDeletedCustom);
        });
    });

    $app->get('', function ($req, $res, $args) use ($app) {
        global $db;
        $dashBoards = array();
        $dashBoardsObj = $db->query("SELECT * FROM dashboards");
        while ($dashBoard = $db->fetchByAssoc($dashBoardsObj))
            $dashBoards[] = $dashBoard;
        return $res->withJson($dashBoards);
    });
    $app->get('/{id}', function($req, $res, $args) use ($app) {
        global $db;
        $dashBoardBean = BeanFactory::getBean('Dashboards', $args['id']);
        $dashBoardBean = $dashBoardBean->retrieve($args['id']);
        return $res->withJson($dashBoardBean->components);
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
        return $res->withJson(array('status' => $status));
    });
});