<?php

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
