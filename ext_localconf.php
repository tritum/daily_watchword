<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Tritum.' . $_EXTKEY,
    'DailyWatchword',
    ['Watchword' => 'index'],
    ['Watchword' => 'index']
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Tritum\DailyWatchword\Task\ImportWatchwordTask::class] = [
    'extension' => $_EXTKEY,
    'title' => 'Download Watchwords',
    'description' => 'Downloads the Watchwords of the current year',
];
