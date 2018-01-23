<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Hook for adding switch icon to website users
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions'][] = 'Cabag\CabagLoginas\Hook\RecordListHook';

// Hook to check for redirection
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp'][] = 'Cabag\CabagLoginas\Hook\PostUserLookupHook->postUserLookUp';

// Trigger authentication even if login is not requested explicitely
$GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['FE_alwaysFetchUser'] = 1;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService($_EXTKEY, 'auth', 'Cabag\\CabagLoginas\\Service\\LoginAsService' /* sv key */,
	array(

		'title' => 'Login as Service',
		'description' => 'Authenticate a frontend user using a link',

		'subtype' => 'getUserFE,authUserFE',

		'available' => TRUE,
		'priority' => 70,
		'quality' => 70,

		'os' => '',
		'exec' => '',

		'className' => Cabag\CabagLoginas\Service\LoginAsService::class
	)
);

