<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cabag_loginas".
 *
 * Auto generated 14-04-2015 13:30
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
    'title' => 'CAB Login As',
    'description' => 'Within the backend you have a button in the fe_user table and in the upper right corner to quickly login as this fe user in frontend.',
    'category' => 'be',
    'version' => '2.1.2',
    'state' => 'stable',
    'uploadfolder' => true,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Dimitri Koenig, Tizian Schmidlin, Lorenz Ulrich, Thomas Löffler',
    'author_email' => 'dk@cabag.ch, st@cabag.ch, info@visol.ch, loeffler@spooner-web.de',
    'author_company' => '',
    'constraints' =>
        array(
            'depends' =>
                array(
                    'typo3' => '7.6.0-8.7.99',
                ),
            'conflicts' =>
                array(),
            'suggests' =>
                array(),
        ),
);
