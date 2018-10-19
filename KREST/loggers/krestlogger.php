<?php

/**
 * MIDDLEWARE / KREST Logger
 */
$mw = function ($request, $response, $next)
{
    global $db, $current_user;
    $starting_time = microtime(true);

    $route = $request->getAttribute('route');
    $log = (object) [];
    // if no route was found... $route = null
    if($route)
    {
        $log->route = $route->getPattern();
        $log->method = $route->getMethods()[0];
        $log->args = json_encode($route->getArguments());
    }

    $log->url = (string) $request->getUri();    // will be converted to the complete url when be used in text context, therefore it is cast to a string...

    $log->ip = $request->getServerParam('REMOTE_ADDR');
    $log->get_params = json_encode($_GET);
    $log->post_params = $request->getBody()->getContents();
    $log->requested_at = date('Y-m-d H:i:s');
    // $current_user is an empty beansobject if the current route doesn't need any authentication...
    $log->user_id = $current_user->id;
    // and session is also missing!
    $log->session_id = session_id();
    //var_dump($request->getParsedBody(), $request->getParams());

    // check if this request has to be logged by some rules...
    $sql = "SELECT COUNT(id) cnt FROM syskrestlogconfig WHERE 
              (route = '{$log->route}' OR route = '*' OR '{$log->route}' LIKE route) AND
              (method = '{$log->method}' OR method = '*') AND
              (user_id = '{$log->user_id}' OR user_id = '*') AND
              (ip = '{$log->ip}' OR ip = '*') AND
              is_active = 1";
    $res = $db->query($sql);
    $row = $db->fetchByAssoc($res);
    if( $row['cnt'] > 0 ) {
        $logging = true;
        // write the log...
        $log->id = null;
        $id = $db->insertQuery('syskrestlog', (array) $log);
        $log->id = $id;

        ob_start();
    }
    else
        $logging = false;

    // do the magic...
    $response = $next($request, $response);

    if( $logging )
    {
        $log->http_status_code = $response->getStatusCode();
        $log->runtime = (microtime(true) - $starting_time)*1000;
        $log->response = $db->quote(ob_get_contents());
        ob_end_flush();

        // if the endpoint didn't use echo... instead the response object ist correctly returned by the endpoint
        if(!$log->response)
            $log->response = $response->getBody();
        // update the log...
        $result = $db->updateQuery('syskrestlog', ['id' => $log->id], (array) $log);
        //var_dump($result, $db->last_error);
    }
    return $response;
};

$app->add($mw);