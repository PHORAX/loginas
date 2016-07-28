<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE') {
	// register the class as toolbar item
	$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems']['cabag_loginas'] = \Cabag\CabagLoginas\Hook\ToolbarItemHook::class;
}