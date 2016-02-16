<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$suffix = version_compare(TYPO3_version, '6.2', '>=') ? '.6.2' : '';

//$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['recStatInfoHooks'][] = t3lib_extMgm::extPath($_EXTKEY).'class.tx_cabagloginas.php:tx_cabagloginas->getLoginAsIconInTable';
$TYPO3_CONF_VARS['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions'][] = t3lib_extMgm::extPath($_EXTKEY) . 'class.tx_cabagloginas_makecontrolhook' . $suffix . '.php:tx_cabagloginas_makecontrolhook';

// Hook to check for redirection
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp'][] =
	'EXT:cabag_loginas/class.tx_cabagloginas_userauth.php:tx_cabagloginas_userauth->postUserLookUp';

$TYPO3_CONF_VARS['SVCONF']['auth']['setup']['FE_alwaysFetchUser'] = 1;

t3lib_extMgm::addService($_EXTKEY, 'auth' /* sv type */, 'tx_cabagloginas_sv1' /* sv key */,
	array(

		'title' => 'Login as Sergice',
		'description' => 'Login a frontend user using login as link',

		'subtype' => 'getUserFE,authUserFE',

		'available' => TRUE,
		'priority' => 70,
		'quality' => 70,

		'os' => '',
		'exec' => '',

		'classFile' => t3lib_extMgm::extPath($_EXTKEY) . 'sv1/class.tx_cabagloginas_sv1.php',
		'className' => 'tx_cabagloginas_sv1',
	)
);