<?php
/**
 * introduced in version 20180100
 * this module is related to CompanyCodes.
 * When companycodes represent something like a sales organisations,
 * ContactCCDetails represent something like terms and conditions
 * for an account for the related sales organisation
 */
$moduleList[] = 'ContactCCDetails';
$beanList['ContactCCDetails'] = 'ContactCCDetail';
$beanFiles['ContactCCDetail'] = 'modules/ContactCCDetails/ContactCCDetail.php';
