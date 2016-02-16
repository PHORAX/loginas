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

require_once(t3lib_extMgm::extPath('cabag_loginas').'class.tx_cabagloginas.php');
require_once(PATH_typo3.'interfaces/interface.localrecordlist_actionsHook.php');

class tx_cabagloginas_makecontrolhook implements localRecordList_actionsHook {
	var $loginAsObj = null;

	public function tx_cabagloginas_makecontrolhook() {
		$this->loginAsObj = new tx_cabagloginas;
	}
	
	public function makeClip($table, $row, $cells, &$parentObject) {
		return $cells;
	}
	
	public function makeControl($table, $row, $cells, &$parentObject) {
		if($table == 'fe_users') {
			$tempcells = array();
			foreach($cells as $key => $value) {
				if(strpos($value, 'clear.gif') === false) {
					$tempcells[$key] = $value;
				}
			}
			$cells = $tempcells;
			$loginas = $this->loginAsObj->getLoginAsIconInTable($row['uid']);
			$cells['loginas'] = $loginas;
		}
		return $cells;
	}
	
	public function renderListHeader($table, $currentIdList, $headerColumns, &$parentObject) {
		return $headerColumns;
	}
	
	public function renderListHeaderActions($table, $currentIdList, $cells, &$parentObject) {
		return $cells;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_loginas/class.tx_cabagloginas.php']);
}

?>
