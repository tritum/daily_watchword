<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Tritum.' . $_EXTKEY,
    'DailyWatchword',
    'Daily Watchword'
);

$TCA['tt_content']['types']['list']['subtypes_excludelist']['dailywatchword_dailywatchword'] = 'select_key,pages,recursive';
