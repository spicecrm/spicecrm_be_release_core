<?php

/**
 * determine if we have a lgnauge specific filter set to be applied
 */
global $sugar_config;
$languagefilter = [];
if($sugar_config['fts']['languagefilter']){
    $languagefilter[] = $sugar_config['fts']['languagefilter'];
}

$elasticAnalyzers = array(
    "spice_standard" => array(
        "type" => "custom",
        "tokenizer" => "standard",
        "filter" => $languagefilter
    ),
    "spice_standard_all" => array(
        "type" => "custom",
        "tokenizer" => "spice_standard_all",
        "filter" => $languagefilter
    ),
    "spice_ngram" => array(
        "type" => "custom",
        "tokenizer" => "spice_ngram",
        "filter" => array_merge(["lowercase"],$languagefilter)
    ),
    "spice_ngram_all" => array(
        "type" => "custom",
        "tokenizer" => "spice_ngram_all",
        "filter" => array_merge(["lowercase"],$languagefilter)
    ),
    "spice_html" => array(
        "type" => "custom",
        "tokenizer" => "spice_ngram",
        "filter" => array_merge(["lowercase"],$languagefilter),
        "char_filter" => ["html_strip"]
    ),
    "spice_edgengram" => array(
        "type" => "custom",
        "tokenizer" => "spice_edgengram",
        "filter" => array_merge(["lowercase"],$languagefilter)
    ),
    "spice_email" => array(
        "type" => "custom",
        "tokenizer" => "spice_email",
        "filter" => ["lowercase"]
    )
);