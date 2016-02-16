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

class tx_cabagloginas {
	function getHREF($userid) {
		$verification = md5($GLOBALS['$TYPO3_CONF_VARS']['SYS']['encryptionKey'].$userid);
		$link = 'http://'.$_SERVER['SERVER_NAME'].'?tx_felogin_pi1[noredirect]=1&tx_cabagloginas[userid]='.$userid.'&tx_cabagloginas[verification]='.$verification;
		return $link;
	}
	function getLink($data) {
		$label = $data['label'] . ' ' . $data['row']['name'];
		$link = $this->getHREF($data['row']['uid']);
		$content = '<a href="'.$link.'" target="_blank" style="text-decoration:underline;">'.$label.'</a>'; 
		return $content;
	}

	function getLoginAsIconInTable($params) {
		if($params[0] == 'fe_users') {
			$label = '<img src="/typo3/sysext/t3skin/icons/gfx/su_back.gif" />';
			$link = $this->getHREF($params[1]);
			$content = '<a href="'.$link.'" target="_blank">'.$label.'</a>'; 
			return $content;
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas.php']);
}

?>
