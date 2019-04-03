<?php
$elasticTokenizers = array(
    "spice_ngram" => array(
        "type" => "nGram",
        "min_gram" => "3",
        "max_gram" => "20",
        "token_chars" => [
            "letter",
            "digit"
        ]
    ),
    "spice_edgengram" => array(
        "type" => "edge_ngram",
        "min_gram" => "3",
        "max_gram" => "20",
        "token_chars" => [
            "letter",
            "digit"
        ]
    ),
    "spice_email" => array(
        "type" => "uax_url_email",
        "max_token_length" => 5
    )
);