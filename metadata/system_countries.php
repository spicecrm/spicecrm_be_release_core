<?php

$dictionary['syscountries'] = array(
    'table' => 'syscountries',
    'comment' => 'holds a list fo all countries for selecttion and translation',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'cc' => array(
            'name' => 'cc',
            'type' => 'varchar',
            'len' => 2,
            'comment' => 'the 2 digit country code according to ISO3166'
        ),
        'e164' => array(
            'name' => 'e164',
            'type' => 'varchar',
            'len' => 3,
            'comment' => 'the e164 country code for the country'
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 50,
            'comment' => 'the name of the label for language dependent display of the country name'
        ),
        'addressformat' => array(
            'name' => 'addressformat',
            'type' => 'varchar',
            'len' => 250,
            'comment' => 'the format of the address like {street}, {postalcode} {city}, {statename}, {countryname}'
        )
    ),
    'indices' => array(
        array(
            'name' => 'idx_syscountries',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['syscountrystates'] = array(
    'table' => 'syscountrystates',
    'comment' => 'holds states per country - subdivision according to ISO3166-2',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'cc' => array(
            'name' => 'cc',
            'type' => 'varchar',
            'len' => 2,
            'comment' => 'the 2 digit country code according to ISO3166'
        ),
        'sc' => array(
            'name' => 'sc',
            'type' => 'varchar',
            'len' => 5,
            'comment' => 'the subdivison code used internally - used for SAP Integration as SAP uses different values'
        ),
        'iso3166' => array(
            'name' => 'iso3166',
            'type' => 'varchar',
            'len' => 5,
            'comment' => 'the subdivison code according to ISO3166'
        ),
        'google_aa' => array(
            'name' => 'google_aa',
            'type' => 'varchar',
            'len' => 50,
            'comment' => 'the subdivison code according to Google which is not necessarily conforming'
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 50,
            'comment' => 'the name of the label for language dependent display of the state/subdivision name'
        )
    ),
    'indices' => array(
        array(
            'name' => 'idx_syscountrystates',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);
