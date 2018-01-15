<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'daily_watchword',
    'description' => 'Displays the Watchword of the day',
    'category' => 'plugin',
    'author' => 'Falko Linke',
    'author_email' => 'falko.linke@tritum.de',
    'author_company' => 'Tritum',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => false,
    'version' => '0.9.2',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
