<?php
/**
 * Class SpiceUILoader
 * Utility class for SpiceCRM backend
 * get records from referenced database
 * Endpoint for default values is located in SpiceCRM\modules\SystemDeploymentPackages\SystemDeploymentPackageSource
 */
namespace SpiceCRM\modules\SystemUI;
use SpiceCRM\modules\SystemDeploymentPackages\SystemDeploymentPackageSource;

class SpiceUILoader
{

    public $db;
    public $endpoint;
    public $curl;

    /**
     * SpiceUIConfLoader constructor.
     * @param null $endpoint introduced with CR1000133
     */
    public function __construct($endpoint = null)
    {
        global $sugar_config;

        // get database object
        $this->db = \DBManagerFactory::getTypeInstance($sugar_config['dbconfig']['db_type']);
        if (!$this->db->connect($sugar_config['dbconfig']))
            die('database connection failed');

        // set default endpoint
        if (empty($this->endpoint)) {
            $this->endpoint = SystemDeploymentPackageSource::getPublicSource();
        }
        // set selected endpoint
        if (!empty($endpoint)) {
            $this->endpoint = $endpoint;
            // cleanup endpoint (ending /)
            if (substr($this->endpoint, -1) != "/") {
                $this->endpoint .= "/";
            }
        }

        // catch empty endpint
        if(empty($this->endpoint) || strlen($this->endpoint) < 5){
            $GLOBALS['log']->error("No endpoint defined");
        }

        // initialize curl
        $this->curl = curl_init();
    }



    public function callMethod($method, $route, $getParams = null, $postParams = array())
    {
        if (!empty($getParams) && is_array($getParams))
            $getParams = "?" . http_build_query($getParams);
        $url = $this->endpoint . $route . $getParams;
//        file_put_contents('spicecrm.log', $url."\n", FILE_APPEND);

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
