<?php
$elasticTokenizers = array(
    "spice_ngram" => array(
        "type" => "nGram",
        "min_gram" => "3",
        "max_gram" => "20",
    ),
    "spice_email" => array(
        "type" => "uax_url_email",
        "max_token_length" => "5"
    )
);