<?php
namespace Cabag\CabagLoginas\Hook;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class ToolbarItemHook implements \TYPO3\CMS\Backend\Toolbar\ToolbarItemHookInterface {
	protected $backendReference;

	protected $users = array();

	protected $EXTKEY = 'cabag_loginas';

	public function __construct(\TYPO3\CMS\Backend\Controller\BackendController &$backendReference = NULL) {
		$GLOBALS['LANG']->includeLLFile('EXT:cabag_loginas/Resources/Private/Language/locallang_db.xlf');
		$this->backendReference = $backendReference;

		$email = $GLOBALS['BE_USER']->user['email'];

		$this->users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'fe_users',
			'email = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($email, 'fe_users') . ' AND disable = 0 AND deleted = 0',
			'',
			'',
			'15'
		);
	}

	public function checkAccess() {
		$conf = $GLOBALS['BE_USER']->getTSConfig('backendToolbarItem.tx_cabagloginas.disabled');

		return ($conf['value'] == 1 ? FALSE : TRUE);
	}

	public function render() {
		$this->backendReference->addCssFile('cabag_loginas', ExtensionManagementUtility::extRelPath($this->EXTKEY) . 'Resources/Public/Stylesheets/cabag_loginas.css');
		$this->backendReference->addJavascriptFile(ExtensionManagementUtility::extRelPath($this->EXTKEY) . 'Resources/Public/JavaScripts/cabag_loginas.js');

		$toolbarMenu = array();

		$title = $GLOBALS['LANG']->getLL('fe_users.tx_cabagloginas_loginas', TRUE);
		$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_loginas']);
		$defLinkText = trim($ext_conf['defLinkText']);
		if (empty($defLinkText) || strstr($defLinkText, '#') === FALSE || strstr($defLinkText, 'password') !== FALSE) {
			$defLinkText = '[#pid# / #uid#] #username# (#email#)';
		}

		if (count($this->users)) {
			if (count($this->users) == 1) {
				$title .= ' ' . $this->formatLinkText($this->users[0], $defLinkText);
				$toolbarMenu[] = $this->getLoginAsIconInTable($this->users[0], $title);
			} else {
				$toolbarMenu[] = '<a href="#" class="toolbar-item"><img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($this->backPath, 'gfx/su_back.gif', 'width="16" height="16"') . ' title="' . $title . '" alt="' . $title . '" /></a>';

				$toolbarMenu[] = '<ul class="toolbar-item-menu" style="display: none;">';
				$userIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('apps-pagetree-folder-contains-fe_users', array('style' => 'background-position: 0 10px;'));
				foreach ($this->users as $user) {
					$linktext = $this->formatLinkText($user, $defLinkText);
					$link = $this->getHREF($user);
					$toolbarMenu[] = '<li><a href="' . htmlspecialchars($link) . '" title="' . $title . '" target="_blank">' . $userIcon . $linktext . '</a></li>';
				}

				$toolbarMenu[] = '</ul>';
			}

			return implode("\n", $toolbarMenu);
		}
	}

	public function formatLinkText($user, $defLinkText) {
		foreach ($user as $key => $value) {
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

	public function getHREF($user) {
		$parameterArray = array();
		$parameterArray['userid'] = (string) $user['uid'];
		$parameterArray['timeout'] = (string) $timeout = time() + 3600;
		// Check user settings for any redirect page
		if ($user['felogin_redirectPid']) {
			$parameterArray['redirecturl'] = $this->getRedirectUrl($user['felogin_redirectPid']);
		} else {
			// Check group settings for any redirect page
			$userGroup = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
				'fe_groups.felogin_redirectPid',
				'fe_users, fe_groups',
				'fe_groups.felogin_redirectPid != "" AND fe_groups.uid IN (fe_users.usergroup) AND fe_users.uid = ' . $user['uid']
			);
			if (is_array($userGroup) && !empty($userGroup['felogin_redirectPid'])) {
				$parameterArray['redirecturl'] = $this->getRedirectUrl($userGroup['felogin_redirectPid']);
			} elseif (rtrim(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), '/') !== ($domain = $this->getRedirectForCurrentDomain($user['pid']))) {
				// Any manual redirection defined in sys_domain record
				$parameterArray['redirecturl'] = rawurlencode($domain);
			}
		}
		$ses_id = $GLOBALS['BE_USER']->user['ses_id'];
		$parameterArray['verification'] = md5($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] . $ses_id . serialize($parameterArray));
		$link = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . '?' . \TYPO3\CMS\Core\Utility\GeneralUtility::implodeArrayForUrl('tx_cabagloginas', $parameterArray);

		return $link;
	}

	public function getLink($data) {
		$label = $data['label'] . ' ' . $data['row']['username'];
		$link = $this->getHREF($data['row']);
		$content = '<a href="' . $link . '" target="_blank" style="text-decoration:underline;">' . $label . '</a>';

		return $content;
	}

	public function getLoginAsIconInTable($user, $title = '') {
		$switchUserIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-system-backend-user-emulate', array('style' => 'background-position: 0 10px;'));
		$link = $this->getHREF($user);
		$content = '<a title="' . $title . '" href="' . $link . '" target="_blank">' . $switchUserIcon . '</a>';
		return $content;
	}

	/**
	 * Finds the redirect link for the current domain.
	 *
	 * @param integer $pid Page id the user is stored in
	 *
	 * @return string '../' if nothing was found, the link in the form of http://www.domain.tld/link/page.html otherwise.
	 */
	public function getRedirectForCurrentDomain($pid) {
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_loginas']);
		$domain = \TYPO3\CMS\Backend\Utility\BackendUtility::getViewDomain($pid);
		$domainArray = parse_url($domain);

		if (empty($extConf['enableDomainBasedRedirect'])) {
			return $domain;
		}

		$rowArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'domainName, tx_cabagfileexplorer_redirect_to',
			'sys_domain',
			'hidden = 0 AND domainName = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($domainArray['host'], 'sys_domain'),
			'',
			'',
			1
		);

		if (count($rowArray) === 0 || (trim($rowArray[0]['tx_cabagfileexplorer_redirect_to'])) === '') {
			return $domain;
		}

		$domain = 'http' . (\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SSL') ? 's' : '') . '://' . $rowArray[0]['domainName'] . '/' .
			ltrim($rowArray[0]['tx_cabagfileexplorer_redirect_to'], '/');

		return $domain;
	}

	/**
	 * @param integer $pageId
	 *
	 * @return string
	 */
	protected function getRedirectUrl($pageId) {
		return rawurlencode(\TYPO3\CMS\Backend\Utility\BackendUtility::getViewDomain($pageId) . '/index.php?id=' . $pageId);
	}
}
