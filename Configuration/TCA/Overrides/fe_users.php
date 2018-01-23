<?php
if (!defined('TYPO3_MODE')) {
        die ('Access denied.');
}
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
