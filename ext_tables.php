<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('cabag_loginas') . 'Resources/PHP/Toolbar.php';



