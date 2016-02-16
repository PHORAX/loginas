<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Nicole Cordes <typo3@cordes.co>
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
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * Frontend hook to support redirection.
 *
 * @author Nicole Cordes <typo3@cordes.co>
 *
 * @package TYPO3
 * @subpackage tx_cabagloginas
 */
class tx_cabagloginas_userauth {

	/**
	 * Looks for any redirection after login.
	 *
	 * @param array $params
	 * @param tslib_feUserAuth $pObj
	 *
	 * @return void
	 */
	public function postUserLookUp($params, &$pObj) {
		if (TYPO3_MODE == 'FE') {
			if (!empty($GLOBALS['TSFE']->fe_user->user['uid'])) {
				$cabagLoginasData = t3lib_div::_GP('tx_cabagloginas');
				if (!empty($cabagLoginasData['redirecturl'])) {
					$partsArray = parse_url(rawurldecode($cabagLoginasData['redirecturl']));
					if (strpos(t3lib_div::getIndpEnv('TYPO3_SITE_URL'), $partsArray['scheme'] . '://' . $partsArray['host'] . '/') === FALSE) {
						$partsArray['query'] .= '&FE_SESSION_KEY=' . rawurlencode(
							$pObj->id . '-' . md5($pObj->id . '/' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'])
						);
					}
					$redirectUrl = (isset($partsArray['scheme']) ? $partsArray['scheme'] . '://' : '') .
						(isset($partsArray['user']) ? $partsArray['user'] .
							(isset($partsArray['pass']) ? ':' . $partsArray['pass'] : '') . '@' : '') .
						(isset($partsArray['host']) ? $partsArray['host'] : '') .
						(isset($partsArray['port']) ? ':' . $partsArray['port'] : '') .
						(isset($partsArray['path']) ? $partsArray['path'] : '') .
						(isset($partsArray['query']) ? '?' . $partsArray['query'] : '') .
						(isset($partsArray['fragment']) ? '#' . $partsArray['fragment'] : '');
					t3lib_utility_Http::redirect($redirectUrl);
				}
			}
		}
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas_userauth.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas_userauth.php']);
}
