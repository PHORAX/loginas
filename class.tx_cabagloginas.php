<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dimitri König <dk@cabag.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_typo3 . 'interfaces/interface.backend_toolbaritem.php');

class tx_cabagloginas implements backend_toolbarItem {
	protected $backendReference;
	protected $users = array();

	protected $EXTKEY = 'cabag_loginas';

	public function __construct(TYPO3backend &$backendReference = null) {
		$GLOBALS['LANG']->includeLLFile('EXT:cabag_loginas/locallang_db.xml');
		$this->backendReference = $backendReference;

		$email = $GLOBALS['BE_USER']->user['email'];

		$this->users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'fe_users',
			'email = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($email, 'fe_users') . ' AND disable = 0 AND deleted = 0'
		);
	}

	public function checkAccess() {
		$conf = $GLOBALS['BE_USER']->getTSConfig('backendToolbarItem.tx_cabagloginas.disabled');
		return ($conf['value'] == 1 ? false : true);
	}

	public function render() {
		$this->backendReference->addCssFile('cabag_loginas', t3lib_extMgm::extRelPath($this->EXTKEY) . 'cabag_loginas.css');
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath($this->EXTKEY).'cabag_loginas.js');

		$toolbarMenu = array();

		$title = $GLOBALS['LANG']->getLL('fe_users.tx_cabagloginas_loginas', true);
		$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_loginas']);
		$defLinkText = trim($ext_conf['defLinkText']);
		if(empty($defLinkText) || strstr($defLinkText, '#') === false || strstr($defLinkText, 'password') !== false) {
			$defLinkText = '[#pid# / #uid#] #username# (#email#)';
		}

		if(count($this->users)) {
			if(count($this->users) == 1) {
				$title .= ' ' . $this->formatLinkText($this->users[0], $defLinkText);
				$toolbarMenu[] = $this->getLoginAsIconInTable($this->users[0]['uid'], $title);
			} else {
				$toolbarMenu[] = '<a href="#" class="toolbar-item"><img'.t3lib_iconWorks::skinImg($this->backPath, 'gfx/su_back.gif', 'width="16" height="16"').' title="'.$title.'" alt="'.$title.'" /></a>';

				$toolbarMenu[] = '<ul class="toolbar-item-menu" style="display: none;">';

				foreach($this->users as $user) {
					$linktext = $this->formatLinkText($user, $defLinkText);
					$link = $this->getHREF($user['uid']);
					$toolbarMenu[] = '<li><a href="' . htmlspecialchars($link) . '" target="_blank"><img'.t3lib_iconWorks::skinImg($this->backPath, 'gfx/i/fe_users.gif', 'width="16" height="16"').' title="'.$title.'" alt="'.$title.'" /> ' . $linktext . '</a></li>';
				}

				$toolbarMenu[] = '</ul>';
			}

			return implode("\n", $toolbarMenu);
		}
	}

	public function formatLinkText($user, $defLinkText) {
		foreach($user as $key => $value) {
			$defLinkText = str_replace('#' . $key . '#', $value, $defLinkText);
		}
		return $defLinkText;
	}

	public function getAdditionalAttributes() {
		if (count($this->users)) {
			return ' id="tx-cabagloginas-menu"';
		} else {
			return '';
		}
	}

	function getHREF($userid) {
		$timeout = time()+300;
		$ses_id = $GLOBALS['BE_USER']->user['ses_id'];
		$verification = md5($GLOBALS['$TYPO3_CONF_VARS']['SYS']['encryptionKey'].$userid.$timeout.$ses_id);
		$link = 'http://'.$_SERVER['SERVER_NAME'].'?tx_cabagloginas[timeout]='.$timeout.'&tx_cabagloginas[userid]='.$userid.'&tx_cabagloginas[verification]='.$verification;
		return $link;
	}
	function getLink($data) {
		$label = $data['label'] . ' ' . $data['row']['username'];
		$link = $this->getHREF($data['row']['uid']);
		$content = '<a href="'.$link.'" target="_blank" style="text-decoration:underline;">'.$label.'</a>';
		return $content;
	}

	function getLoginAsIconInTable($userid, $title = '') {
		$label = '<img'.t3lib_iconWorks::skinImg($this->backPath, 'gfx/su_back.gif', 'width="16" height="16"').' title="'.$title.'" alt="'.$title.'" />';
		$link = $this->getHREF($userid);
		$content = '<a class="toolbar-item" href="'.$link.'" target="_blank">'.$label.'</a>';
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas.php']);
}

?>
