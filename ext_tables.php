<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

require_once(t3lib_extMgm::extPath('cabag_loginas') . 'class.tx_cabagloginas.php');

$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = t3lib_extMgm::extPath('cabag_loginas') . 'cabagloginas_toolbar.php';

$tempColumns = array (
	'tx_cabagloginas_loginas' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:cabag_loginas/locallang_db.xml:fe_users.tx_cabagloginas_loginas',		
		'config' => array (
			'type' => 'user',
			'userFunc' => 'tx_cabagloginas->getLink',
		)
	),
);


t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('fe_users','tx_cabagloginas_loginas', '', 'after:lastlogin');
?>