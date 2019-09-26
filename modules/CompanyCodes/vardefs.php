<?php

$dictionary['CompanyCode'] = array(
    'table' => 'companycodes',
    'audited' => true,
    'fields' => array(
        'registration' => array(
            'name' => 'registration',
            'vname' => 'LBL_REGISTRATION',
            'type' => 'varchar',
            'len' => 80
        ),
        'vatid' => array(
            'name' => 'vatid',
            'vname' => 'LBL_VATID',
            'type' => 'varchar',
            'len' => 40,
            'required' => false,
            'reportable' => true,
            'massupdate' => false,
        ),
        'company_address_street' =>
        array(
            'name' => 'company_address_street',
            'vname' => 'LBL_COMPANY_ADDRESS_STREET',
            'type' => 'varchar',
            'len' => '150',
            'group' => 'company_address'
        ),
        'company_address_city' =>
        array(
            'name' => 'company_address_city',
            'vname' => 'LBL_COMPANY_ADDRESS_CITY',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'company_address'
        ),
        'company_address_state' =>
        array(
            'name' => 'company_address_state',
            'vname' => 'LBL_COMPANY_ADDRESS_STATE',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'company_address'
        ),
        'company_address_postalcode' =>
        array(
            'name' => 'company_address_postalcode',
            'vname' => 'LBL_COMPANY_ADDRESS_POSTALCODE',
            'type' => 'varchar',
            'len' => '20',
            'group' => 'company_address'
        ),
        'company_address_country' =>
        array(
            'name' => 'company_address_country',
            'vname' => 'LBL_COMPANY_ADDRESS_COUNTRY',
            'type' => 'enum',
            'options' => 'countries_dom',
            'group' => 'company_address'
        ),
        'tel' => array(
            'name' => 'tel',
            'vname' => 'LBL_TEL',
            'type' => 'varchar',
            'len' => 50
        ),
        'fax' => array(
            'name' => 'fax',
            'vname' => 'LBL_FAX',
            'type' => 'varchar',
            'len' => 50
        ),
        'email' => array(
            'name' => 'email',
            'vname' => 'LBL_EMAIL',
            'type' => 'varchar',
            'len' => 50
        ),
        'bank_name' => array(
            'name' => 'bank_name',
            'vname' => 'LBL_BANK_NAME',
            'type' => 'varchar',
            'len' => 50
        ),
        'bank_bic' => array(
            'name' => 'bank_bic',
            'vname' => 'LBL_BANK_BIC',
            'type' => 'varchar',
            'len' => 50
        ),
        'bank_iban' => array(
            'name' => 'bank_iban',
            'vname' => 'LBL_BANK_IBAN',
            'type' => 'varchar',
            'len' => 50
        ),
        'header_from' => array(
            'name' => 'header_from',
            'vname' => 'LBL_HEADER_FROM',
            'type' => 'varchar',
            'len' => 255
        ),
        'footer_1' => array(
            'name' => 'footer_1',
            'vname' => 'LBL_FOOTER1',
            'type' => 'varchar',
            'len' => 255,
            'required' => false,
            'reportable' => true,
            'massupdate' => false,
        ),
        'footer_2' => array(
            'name' => 'footer_2',
            'vname' => 'LBL_FOOTER2',
            'type' => 'varchar',
            'len' => 255,
            'required' => false,
            'reportable' => true,
            'massupdate' => false,
        ),
        'footer_3' => array(
            'name' => 'footer_3',
            'vname' => 'LBL_FOOTER3',
            'type' => 'varchar',
            'len' => 255,
            'required' => false,
            'reportable' => true,
            'massupdate' => false,
        ),
        'companyfiscalperiods' => array(
            'name' => 'companyfiscalperiods',
            'vname' => 'LBL_COMPANYFISCALPERIODS',
            'type' => 'link',
            'link_type' => 'one',
            'relationship' => 'cfperiods_companycodes',
            'source' => 'non-db',
            'coment' => 'Link to CompanyFiscalPeriods module'
        ),
        'companycode' => array(
            'name' => 'companycode',
            'vname' => 'LBL_COMPANYCODE',
            'type' => 'varchar',
            'len' => 12
        )
    ),
    'indices' => array(
        array('name' => 'idx_companycodes_id_del', 'type' => 'index', 'fields' => array('id', 'deleted'),),
    ),
    'relationships' => array(
        'companycodes_accountkpis' =>
            array (
                'lhs_module' => 'CompanyCodes',
                'lhs_table' => 'companycodes',
                'lhs_key' => 'id',
                'rhs_module' => 'AccountKPIs',
                'rhs_table' => 'accountkpis',
                'rhs_key' => 'companycode_id',
                'relationship_type' => 'one-to-many',
            )
    ),
    'optimistic_lock' => true,
);

require_once('include/SugarObjects/VardefManager.php');

VardefManager::createVardef('CompanyCodes', 'CompanyCode', array('default', 'assignable'));
global $dictionary; //COMPAT php7.1
$dictionary['CompanyCode']['fields']['name']['vname'] = 'LBL_COMPANYCODE';
