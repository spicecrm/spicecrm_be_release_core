<?php
$dictionary['AccountKPI'] = array(
    'table' => 'accountkpis',
    'fields' => array(
        'account_id' => array (
            'name' => 'account_id',
            'type' => 'id',
            'vname' => 'LBL_ACCOUNT_ID'
        ),
        'account_name' => array (
            'source' => 'non-db',
            'name' => 'account_name',
            'vname' => 'LBL_ACCOUNT',
            'type' => 'relate',
            'len' => '255',
            'id_name' => 'account_id',
            'module' => 'Accounts',
            'link' => 'accounts_link',
            'join_name' => 'accounts',
            'rname' => 'name'
        ),
        'accounts_link' => array (
            'name' => 'accounts_link',
            'type' => 'link',
            'relationship' => 'accounts_accountkpis',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNTS',
        ),
        'companycode_id' => array (
            'name' => 'companycode_id',
            'type' => 'varchar',
            'len' => 36,
            'vname' => 'LBL_COMPANYCODE_ID'
        ),
        'companycode_name' => array (
            'source' => 'non-db',
            'name' => 'companycode_name',
            'vname' => 'LBL_COMPANYCODE',
            'type' => 'relate',
            'len' => '255',
            'id_name' => 'companycode_id',
            'module' => 'CompanyCodes',
            'link' => 'companycodes_link',
            'join_name' => 'companycodes',
            'rname' => 'name'
        ),
        'companycodes_link' => array (
            'name' => 'companycodes_link',
            'type' => 'link',
            'relationship' => 'companycodes_accountkpis',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_COMPANYCODES',
        ),
        'year' => array (
            'name' => 'year',
            'type' => 'int',
            'vname' => 'LBL_YEAR'
        ),
        'period' => array (
            'name' => 'period',
            'type' => 'varchar',
            'vname' => 'LBL_PERIOD',
            'len' => 10
        ),
        'period_date' => array (
            'name' => 'period_date',
            'type' => 'date',
            'vname' => 'LBL_PERIOD'
        ),
        'revenue' => array(
            'name' => 'revenue',
            'vname' => 'LBL_REVENUE',
            'type' => 'currency',
            'dbType' => 'double',
            'required' => true,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => true,
        ),
        'quantity' => array (
            'name' => 'quantity',
            'type' => 'int',
            'vname' => 'LBL_QUANTITY'
        ),
    ),
    'indices' => array(
        'id' => array('name' => 'accountkpis_pk', 'type' => 'primary', 'fields' => array('id')),
        'accounts_accountkpis_account_id' => array('name' => 'accounts_accountkpis_account_id', 'type' => 'index', 'fields' => array('account_id'))
    ),
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('AccountKPIs', 'AccountKPI', array('default', 'assignable'));