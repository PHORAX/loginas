<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('cabag_loginas') . 'Resources/PHP/Toolbar.php';

$tempColumns = array(
	'tx_cabagloginas_loginas' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:cabag_loginas/Resources/Private/Language/locallang_db.xlf:fe_users.tx_cabagloginas_loginas',
		'config' => array(
			'type' => 'user',
			'userFunc' => 'Cabag\CabagLoginas\Hook\ToolbarItemHook->getLink',
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', 'tx_cabagloginas_loginas', '', 'after:lastlogin');

$tx_cabagloginas_extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_loginas']);

// if enabled, add the fields to the sys_domain
if (!empty($tx_cabagloginas_extconf['enableDomainBasedRedirect'])) {
	$tempColumns = array(
		'tx_cabagfileexplorer_redirect_to' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cabag_loginas/Resources/Private/Language/locallang_db.xlf:sys_domain.tx_cabagfileexplorer_redirect_to',
			'config' => array(
				'type' => 'input',
				'size' => '50',
				'max' => '255',
				'eval' => 'trim',
				'default' => ''
			)
		),
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_domain', $tempColumns, 1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_domain', 'tx_cabagfileexplorer_redirect_to');
}