<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


if (version_compare(TYPO3_version, '6.2', '<')) {
	require_once(t3lib_extMgm::extPath('cabag_loginas') . 'class.tx_cabagloginas.php');
}

$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = t3lib_extMgm::extPath('cabag_loginas') . 'cabagloginas_toolbar.php';

$tempColumns = array(
	'tx_cabagloginas_loginas' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:cabag_loginas/locallang_db.xml:fe_users.tx_cabagloginas_loginas',
		'config' => array(
			'type' => 'user',
			'userFunc' => 'tx_cabagloginas->getLink',
		)
	),
);

t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users', $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('fe_users', 'tx_cabagloginas_loginas', '', 'after:lastlogin');

$tx_cabagloginas_extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_loginas']);

// if enabled, add the fields to the sys_domain
if (!empty($tx_cabagloginas_extconf['enableDomainBasedRedirect'])) {
	$tempColumns = array(
		'tx_cabagfileexplorer_redirect_to' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cabag_loginas/locallang_db.xml:sys_domain.tx_cabagfileexplorer_redirect_to',
			'config' => array(
				'type' => 'input',
				'size' => '50',
				'max' => '255',
				'eval' => 'trim',
				'default' => ''
			)
		),
	);

	t3lib_div::loadTCA('sys_domain');
	t3lib_extMgm::addTCAcolumns('sys_domain', $tempColumns, 1);
	t3lib_extMgm::addToAllTCAtypes('sys_domain', 'tx_cabagfileexplorer_redirect_to');
}