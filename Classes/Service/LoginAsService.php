<?php
namespace Cabag\CabagLoginas\Service;

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

class LoginAsService extends \TYPO3\CMS\Sv\AuthenticationService
{
    protected $rowdata;

    public function getUser()
    {
        $row = false;
        $cabag_loginas_data = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_cabagloginas');

        if (isset($cabag_loginas_data['verification'])) {
            $ses_id = $_COOKIE['be_typo_user'];
            $verificationHash = $cabag_loginas_data['verification'];
            unset($cabag_loginas_data['verification']);
            if (md5($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] . $ses_id . serialize($cabag_loginas_data)) === $verificationHash &&
                $cabag_loginas_data['timeout'] > time()) {
                $user = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                    '*',
                    'fe_users',
                    'uid = ' . intval($cabag_loginas_data['userid'])
                );
                if ($user[0]) {
                    $row = $this->rowdata = $user[0];
                    $GLOBALS["TSFE"]->fe_user->setKey('ses', 'tx_cabagloginas', true);
                }
            }
        }

        return $row;
    }

    public function authUser(array $user)
    {
        $OK = 100;

        if ($this->rowdata['uid'] == $user['uid']) {
            $OK = 200;
        }

        return $OK;
    }
}
