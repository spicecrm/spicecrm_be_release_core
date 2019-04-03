<?php

/**
 * determine if we have a lgnauge specific filter set to be applied
 */
global $sugar_config;
$languagefilter = [];
if($sugar_config['fts']['languagefilter']){
    $languagefilter[] = $sugar_config['fts']['languagefilter'];
}


$elasticNormalizers = array(
    "spice_lowercase" => array(
        "type" => "custom",
        "filter" => array_merge(["lowercase"],$languagefilter)
    )
);