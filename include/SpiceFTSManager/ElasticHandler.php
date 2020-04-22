<?php

namespace SpiceCRM\includes\SpiceFTSManager;

class ElasticHandler
{
    var $indexName = 'spicecrm';
    var $indexPrefix = 'spicecrm';
    var $server = '127.0.0.1';
    var $port = '9200';
    var $protocol = 'http';
    var $ssl_verifyhost = 2;
    var $ssl_verifypeer = 1;

    var $version = '7';

    var $standardSettings = array(
        "analysis" => array(
            "filter" => array(),
            "tokenizer" => array(),
            "analyzer" => array()
        ),
        'index' => array(
            'max_ngram_diff' => 20,
        )
    );

    function __construct()
    {
        global $sugar_config;
        $this->server = $sugar_config['fts']['server'];
        $this->port = $sugar_config['fts']['port'];
        $this->indexPrefix = $sugar_config['fts']['prefix'];
        if(isset($sugar_config['fts']['protocol'])){$this->protocol = $sugar_config['fts']['protocol'];}
        if(isset($sugar_config['fts']['ssl_verifyhost'])){$this->ssl_verifyhost = $sugar_config['fts']['ssl_verifyhost'];}
        if(isset($sugar_config['fts']['ssl_verifypeer'])){$this->ssl_verifypeer = $sugar_config['fts']['ssl_verifypeer'];}

        // get the elastic version - only themajor number is important
        $version = $this->getVersion();
        $this->version = substr($version, 0, 1);

        $this->buildSettings();
    }

    /**
     * returns the current elastic version
     */
    function getVersion(){
        if(!isset($_SESSION['SpiceFTS']['elastic'])){
            $response = json_decode($this->query('GET', ''));
            $_SESSION['SpiceFTS']['elastic'] = $response;
        }

        return $_SESSION['SpiceFTS']['elastic']->version->number;
    }

    /**
     * returns the current status of the elastic cluster
     */
    function getStatus(){
        return  json_decode($this->query('GET', ''));
    }

    /**
     * returns the module for the hit
     * this is for 6 and 7 compatibility as the type has been removed with 7 and is no kept in teh source in attribute _module
     *
     * @param $hit
     * @return mixed
     */
    function getHitModule($hit){
        if($hit['_type'] != '_doc'){
            return $hit['_type'];
        } else {
            return $hit['_source']['_module'];
        }
    }

    /**
     * returns the vale for aggs.module.terms.field
     * this is for 6 and 7 compatibility as _type has been replaced by _module in 7
     *
     * @param $hit
     * @return mixed
     */
    function gettModuleTermFieldName(){
        if($this->version == '6'){
            return '_type';
        }
        return '_module';
    }

    /**
     * returns the total for hits
     * this is for 6 and 7 compatibility
     * Elastic 6 returns hits total as $response['hits']['total'] OR  $response['total']
     * Elastic 7 returns hits total as $response['hits']['total']['value']
     * This function will extract the value from proper array structure
     * @param $queryResponse Array
     * @return mixed
     */
    public function getHitsTotalValue($queryResponse){
        if(is_integer($queryResponse['hits']['total'])){
            return $queryResponse['hits']['total'];
        }
        return $queryResponse['hits']['total']['value'];
    }

    /**
     * builds the settings based ont eh various files defined
     */
    function buildSettings()
    {
        $elasticAnalyzers = array();
        if (file_exists('custom/include/SpiceFTSManager/analyzers/spice_analyzers.php'))
            include 'custom/include/SpiceFTSManager/analyzers/spice_analyzers.php';
        else
            include 'include/SpiceFTSManager/analyzers/spice_analyzers.php';
        $this->standardSettings['analysis']['analyzer'] = $elasticAnalyzers;

        $elasticNormalizers = array();
        if (file_exists('custom/include/SpiceFTSManager/normalizers/spice_normalizers.php'))
            include 'custom/include/SpiceFTSManager/normalizers/spice_normalizers.php';
        else
            include 'include/SpiceFTSManager/normalizers/spice_normalizers.php';
        $this->standardSettings['analysis']['normalizer'] = $elasticNormalizers;


        $elasticTokenizers = array();
        if (file_exists('custom/include/SpiceFTSManager/tokenizers/spice_tokenizers.php'))
            include 'custom/include/SpiceFTSManager/tokenizers/spice_tokenizers.php';
        else
            include 'include/SpiceFTSManager/tokenizers/spice_tokenizers.php';
        $this->standardSettings['analysis']['tokenizer'] = $elasticTokenizers;

        $elasticFilters = array();
        if (file_exists('custom/include/SpiceFTSManager/filters/spice_filters.php'))
            include 'custom/include/SpiceFTSManager/filters/spice_filters.php';
        else
            include 'include/SpiceFTSManager/filters/spice_filters.php';
        $this->standardSettings['analysis']['filter'] = $elasticFilters;
    }

    private function getAllIndexes()
    {
        global $db;

        $indexes = array();

        //catch installation process and abort. table sysfts will not exist at the point during installation
        if( !empty( $GLOBALS['installing'] ))
            return array();

        $indexObjects = $db->query("SELECT module FROM sysfts");
        while ($indexObject = $db->fetchByAssoc($indexObjects)) {
            $indexes[] = $this->indexPrefix . strtolower($indexObject['module']);
        }

        return $indexes;
    }

    /**
     * returns the stats for the index with the current prefix
     *
     * @return mixed
     */
    function getStats()
    {
        $response = json_decode($this->query('GET',$this->indexPrefix . '*/_stats'), true);
        $response['_prefix'] = $this->indexPrefix;
        return $response;
    }


    /**
     * index a document - create or update
     *
     * @param $module
     * @param $data
     * @return bool|string
     */
    function document_index($module, $data)
    {
        // determein if we send the wait_for param .. for activity stream
        $params = [];
        $indexSettings = SpiceFTSUtils::getBeanIndexSettings($module);
        if($indexSettings['waitfor']) $params['refresh'] = 'wait_for';
        if($this->version == '6') {
            $response = $this->query('POST', $this->indexPrefix . strtolower($module) . '/' . $module . '/' . $data['id'], $params, $data);
        } else {
            $response = $this->query('POST', $this->indexPrefix . strtolower($module) . '/_doc/' . $data['id'], $params, $data);
        }
        return $response;
    }

    /**
     * delete a document from the index
     *
     * @param $module
     * @param $id
     * @return bool|string
     */
    function document_delete($module, $id)
    {
        $params = [];
        $indexSettings = SpiceFTSUtils::getBeanIndexSettings($module);
        if($indexSettings['waitfor']) $params['refresh'] = 'wait_for';

        if($this->version == '6'){
            $response = $this->query('DELETE', $this->indexPrefix . strtolower($module) . '/' . $module . '/' . $id, $params);
        } else {
            $response = $this->query('DELETE', $this->indexPrefix . strtolower($module) . '/_doc/' . $id, $params);
        }
        return $response;
    }

    function search($module, $indexProperties = array(), $searchterm = '', $size = 25, $from = 0)
    {

        global $db;

        $queryParam = array();

        if (!empty($size)) $queryParam['size'] = $size;
        if (!empty($from)) $queryParam['from'] = $from;
        if (!empty($searchterm)) {
            $queryParam['query'] = array(
                "bool" => array(
                    "should" => array(
                        "wildcard" => array(
                            "_all" => "*$searchterm*"
                        )
                    )
                )
            );
        }

        foreach ($indexProperties as $indexProperty) {
            if ($indexProperty['aggregate'])
                $queryParam{'aggs'}{$indexProperty['fieldname']} = array(
                    'terms' => array(
                        'field' => $indexProperty['indexfieldname']
                    )
                );
        }

        $response = json_decode($this->query('POST', $this->indexPrefix . strtolower($module) . '/_search', array(), $queryParam), true);
        return $response;
    }

    function searchModule($module, $queryParam, $size = 25, $from = 0)
    {

        global $db;

        $response = json_decode($this->query('POST', $this->indexPrefix . strtolower($module) . '/_search', array(), $queryParam), true);
        return $response;
    }

    function searchModules($modules, $queryParam, $size = 25, $from = 0)
    {
        global $db;

        $modString = '';
        foreach ($modules as $module) {
            if ($modString !== '') $modString .= ',';
            $modString .= $this->indexPrefix . strtolower($module);
        }

        $response = json_decode($this->query('POST', $modString . '/_search', array(), $queryParam), true);
        return $response;
    }

    function filter($filterfield, $filtervalue)
    {
        $response = json_decode($this->query('POST', '/' . $this->indexPrefix . '*/_search', array(), array('query' => array('bool' => array('filter' => array('term' => array($filterfield => $filtervalue)))))), true);
        return $response;
    }

    function createIndex()
    {
        $response = $this->query('PUT', '', array(), array('settings' => $this->standardSettings));
        return $response;
    }


    /**
     * checks the index and returns true or false if the index xists or does not exist
     *
     * @param $module
     * @return bool
     */
    function checkIndex($module, $force = false){
        if(!isset($_SESSION['SpiceFTS']['indexes'][$module]['exists'])) {
            $response = json_decode($this->query('GET', $this->indexPrefix . strtolower($module)));
            $_SESSION['SpiceFTS']['indexes'][$module]['exists'] = $response->{$this->indexPrefix . strtolower($module)} ? true : false;
        }
        return $_SESSION['SpiceFTS']['indexes'][$module]['exists'];
    }

    function deleteIndex($module)
    {
        $response = $this->query('DELETE', $this->indexPrefix . strtolower($module));
        return $response;
    }

    function getIndex()
    {
        $response = $this->query('GET', '_cat/indices?v'); //PHP7.1 compatibility: 2 parameters expected!
        return $response;
    }

    function getMapping($module)
    {
        $response = $this->query('GET', '_mapping/' . $this->indexPrefix . strtolower($module));
        return $response;
    }

    /**
     * puts the mapping for a module and creates the index
     * contains elastic 6 compatibility .. to be removed in future version
     *
     * @param $module
     * @param $properties
     * @return bool|string
     */
    function putMapping($module, $properties)
    {
        if($this->version == '6'){
            $mapping = array(
                '_all' => array(
                    'analyzer' => 'spice_ngram'
                ),
                'properties' => $properties
            );
            $response = $this->query('PUT', SpiceFTSUtils::getIndexNameForModule($module), array(), array(
                    'settings' => $this->standardSettings,
                    'mappings' => array(
                        $module => $mapping
                    )
                )
            );
        } else {
            $mapping = array(
                'properties' => $properties
            );
            $response = $this->query('PUT', SpiceFTSUtils::getIndexNameForModule($module), array(), array(
                    'settings' => $this->standardSettings,
                    'mappings' => $mapping
                )
            );
        }
        return $response;
    }

    /**
     * exeutes the query on the elastic index
     *
     * @param $method
     * @param $url
     * @param array $params
     * @param array $body
     * @return bool|string
     */
    function query($method, $url, $params = array(), $body = array())
    {
        global $sugar_config;

        $data_string = !empty($body) ? json_encode($body) : '';

        $cURL = $this->protocol . '://' . $this->server . ':' . $this->port . '/';
        if (!empty($url)) $cURL .=  $url;

        if(!empty($params)){
            if(substr($cURL, -1 ) != '?')
                $cURL.= '?';
            $cURL.= http_build_query($params);
        }

        $ch = curl_init($cURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );


        $start = microtime();
        $result = curl_exec($ch);
        $end = microtime();

        $rt_local = microtime_diff($start, $end) * 1000;
        $resultdec = json_decode($result);

        switch ($sugar_config['fts']['loglevel']) {
            case '2':
                $this->addLogEntry($method, $cURL, @$resultdec->status, $data_string, $result ); # , $rt_local, $resultdec->took);
                break;
            case '1':
                if ( @$resultdec->status > 0 )
                    $this->addLogEntry($method, $cURL, @$resultdec->status, $data_string, $result ); # , $rt_local, $resultdec->took);
                break;
        }

        return $result;
    }

    function bulk($lines = array())
    {
        global $sugar_config;

        $body = implode("\n", $lines) . "\n";

        $cURL = $this->protocol . '://' . $this->server . ':' . $this->port . '/_bulk';
        $ch = curl_init($cURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body))
        );

        $start = microtime();
        $result = curl_exec($ch);
        $end = microtime();

        $rt_local = microtime_diff($start, $end) * 1000;
        $resultdec = json_decode($result);

        switch ($sugar_config['fts']['loglevel']) {
            case '2':
                $this->addLogEntry('POST', $cURL, @$resultdec->status, $body, $result ); # , $rt_local, $resultdec->took);
                break;
            case '1':
                if ( @$resultdec->status > 0 )
                    $this->addLogEntry('POST', $cURL, @$resultdec->status, $body, $result ); # , $rt_local, $resultdec->took);
                break;
        }

        return $resultdec;
    }

    /**
     * adds a log entry to the fts log
     *
     * @param $method
     * @param $url
     * @param null $status
     * @param $request
     * @param $response
     * @return bool
     */
    private function addLogEntry($method, $url, $status=null, $request, $response ) # , $rtlocal, $rtremote )
    {
        global $db, $timedate;
        //catch installation process and abort. table sysftslog will not exist at the point during installation
        if( !empty( $GLOBALS['installing'] ))
            return false;
        $db->query( sprintf('INSERT INTO sysftslog ( id, date_created, request_method, request_url, response_status, index_request, index_response ) values( "%s", now(), "%s", "%s", "%s", "%s", "%s" )', create_guid(),  $db->quote( $method ), $db->quote( $url ), $db->quote( $status ), $db->quote( str_replace("\\n", "", $request) ), $db->quote( $response )));
    }
}
