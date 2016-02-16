<?php

if (!defined('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE == 'BE') {
		// register the class as toolbar item
	$GLOBALS['TYPO3backend']->addToolbarItem('cabag_loginas', 'tx_cabagloginas');
}

?>