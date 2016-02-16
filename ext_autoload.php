<?php
$extensionClassesPath = t3lib_extMgm::extPath('cabag_loginas') . '/';
$suffix = version_compare(TYPO3_version, '6.2', '>=') ? '.6.2' : '';

$classes = array(
	'tx_cabagloginas' => '',
	'tx_cabagloginas_makecontrolhook' => '',
	'tx_cabagloginas_userauth' => '',
	'tx_cabagloginas_sv1' => 'sv1/',
);

foreach ($classes as $key => &$path) {
	if (file_exists($extensionClassesPath . $path . 'class.' . $key . $suffix . '.php')) {
		$path = $extensionClassesPath . $path . 'class.' . $key . $suffix . '.php';
	} else {
		$path = $extensionClassesPath . $path . 'class.' . $key . '.php';
	}
}

return $classes;
