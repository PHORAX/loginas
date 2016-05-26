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

class RecordListHook implements \TYPO3\CMS\Recordlist\RecordList\RecordListHookInterface {

	/**
	 * @var $loginAsObj \Cabag\CabagLoginas\Hook\ToolbarItemHook
	 */
	public $loginAsObj = NULL;

	public function __construct() {
		$this->loginAsObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Cabag\CabagLoginas\Hook\ToolbarItemHook');
	}

	public function makeClip($table, $row, $cells, &$parentObject) {
		return $cells;
	}

	public function makeControl($table, $row, $cells, &$parentObject) {
		if ($table == 'fe_users') {
			$tempcells = array();
			foreach ($cells as $key => $value) {
				if (strpos($value, 'clear.gif') === FALSE) {
					$tempcells[$key] = $value;
				}
			}
			$cells = $tempcells;
			$loginas = $this->loginAsObj->getLoginAsIconInTable($row);
			// moveRight is only used for pages, therefore we use it here
			$cells['moveRight'] = $loginas;
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

