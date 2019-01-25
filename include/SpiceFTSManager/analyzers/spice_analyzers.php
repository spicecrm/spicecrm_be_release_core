<?php
$elasticAnalyzers = array(
    "spice_ngram" => array(
        "type" => "custom",
        "tokenizer" => "spice_ngram",
        //"tokenizer" => "standard",
        "filter" => ["lowercase"]
    ),
    "spice_html" => array(
        "type" => "custom",
        "tokenizer" => "spice_ngram",
        //"tokenizer" => "standard",
        "filter" => ["lowercase"],
        "char_filter" => ["html_strip"]
    ),
    "spice_edgengram" => array(
        "type" => "custom",
        "tokenizer" => "spice_edgengram",
        //"tokenizer" => "standard",
        "filter" => ["lowercase"]
    ),
    "spice_email" => array(
        "type" => "custom",
        "tokenizer" => "spice_email"
    ),
    /*
    "spice_edgengram" => array(
        "type" => "custom",
        // "tokenizer" => "spice_ngram",
        "tokenizer" => "standard",
        "filter" => ["standard", "lowercase", "spice_edgengram"]
    ),
    "spice_email" => array(
        "tokenizer" => "uax_url_email",
        "filter" => ["spice_email", "lowercase", "unique"]
    )
    */
);