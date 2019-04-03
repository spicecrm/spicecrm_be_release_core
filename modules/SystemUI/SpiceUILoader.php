<?php
/**
 * Class SpiceUILoader
 * Utility class for SpiceCRM backend
 * get records from reference database
 * Endpoint to retrieve data is located in config.php
 */
namespace SpiceCRM\modules\SystemUI;

class SpiceUILoader
{

    public $db;
    public $endpoint = 'https://packages.spicecrm.io/';
    public $curl;


    /**
     * SpiceUIConfLoader constructor.
     * @param null $endpoint introduced with CR1000133
     */
    public function __construct($endpoint = null)
    {
        global $sugar_config;
        $this->db = \DBManagerFactory::getTypeInstance($sugar_config['dbconfig']['db_type']);
        if (!$this->db->connect($sugar_config['dbconfig']))
            die('database connection failed');

        //CR1000133: old config til release 201901001
        if(empty($endpoint)){
            $endpoint = $sugar_config['spiceconfigreference']['endpoint'];
        }
        //CR1000133 new config from release 201902001 on
        if(!empty($sugar_config['packageloader']['sources'][0])){
            $endpoint = $sugar_config['packageloader']['sources'][0];
        }


        if (!empty($endpoint)) {
            $this->endpoint = $endpoint;
            if (substr($this->endpoint, -1) != "/") {
                $this->endpoint .= "/";
            }
        }else{
            $GLOBALS['log']->error("No endpoint defined");
        }

        $this->curl = curl_init();
    }


    /**
     * @deprecated CR1000133 since release 201902001 - keep a while for BWC
     * @return bool|string
     */
    public function getRouteBase(){
        global $sugar_config;
        $routebase ="";
        if (isset($sugar_config['spiceconfigreference']['routebase']) && !empty($sugar_config['spiceconfigreference']['routebase'])) {
            $routebase = empty($sugar_config['spiceconfigreference']['routebase']) ? "release" : $sugar_config['spiceconfigreference']['routebase'];
            if (substr($routebase, 0, 1) == "/") {
                $routebase = substr($routebase, 1);
            }
            if (substr($this->routebase, -1) == "/") {
                $routebase = substr($routebase, 0, strlen($routebase) - 1);
            }
        }

        return $routebase;
    }

    public function callMethod($method, $route, $getParams = null, $postParams = array())
    {
        if (!empty($getParams) && is_array($getParams))
            $getParams = "?" . http_build_query($getParams);
        $url = $this->endpoint . $route . $getParams;

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        // turn off ssl check
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_ENCODING, "UTF-8");

//        //post
//        if($method=="POST"){
//            $url = $this->endpoint."/".$route;
//            curl_setopt($this->curl,CURLOPT_POST, true);
//            curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($postParams));
//        }
//        //DELETE
//        if($method=="DELETE"){
//            $url = $this->endpoint."/".$route;
//            curl_setopt($this->curl,CURLOPT_CUSTOMREQUEST, $method);
//            curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($postParams));
//        }

        $response = curl_exec($this->curl);
        if (!$response)
            $GLOBALS['log']->fatal("ERROR curl in " . __CLASS__ . curl_error($this->curl));

        //catch empty response
        if($response == "[]")
            return array('nodata' => 'No data found');

        //decode reponse
        if (!$data = json_decode($response, true))
            $GLOBALS['log']->fatal( 'json_decode error on REST response from reference server. Response: ' . print_r($response, true) . '. URL: '. $url . '. Please check call parameters!' );

        return $data;
    }

    public function close(){
        curl_close($this->curl);
    }

    /**
     * checkt if any change request log is found for a chnage request that hasn't been completed yet
     * if found abort.
     */
    public function hasOpenChangeRequest(){
        //if release_core only: no SystemDeploymentCR class available
        if(!class_exists('SystemDeploymentCR'))
            return false;

        $cr = new \SystemDeploymentCR();
        $list = $cr->getList(array(), 'active');

        if(count($list['list']) > 0)
            return true;
        return false;
    }
}