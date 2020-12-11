<?php
$elasticTokenizers = array(
    "spice_standard_all" => array(
        "type" => "standard",
        "min_gram" => "3",
        "max_gram" => "20",
        "token_chars"=> [
            "letter",
            "digit",
            "punctuation",
            "symbol",
            "whitespace"
        ],
    ),
    "spice_ngram" => array(
        "type" => "nGram",
        "min_gram" => "3",
        "max_gram" => "20",
        "token_chars" => [
            "letter",
            "digit"
        ]
    ),
    "spice_ngram_all" => array(
        "type" => "nGram",
        "min_gram" => "3",
        "max_gram" => "20",
        "token_chars"=> [
            "letter",
            "digit",
            "punctuation",
            "symbol",
            "whitespace",
            "custom"
        ],
        "custom_token_chars" => "+&"
    ),
    "spice_ngram_all_search" => array(
        "type" => "nGram",
        "min_gram" => "3",
        "max_gram" => "20",
        "token_chars"=> [
            "letter",
            "digit",
            "punctuation",
            "symbol",
            "custom"
        ],
        "custom_token_chars" => "+&"
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
