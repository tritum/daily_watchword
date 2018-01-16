<?php

namespace Tritum\DailyWatchword\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Falko Linke <falko.linke@tritum.de>, TRITUM GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Tritum\DailyWatchword\Utility\DailyWatchwordFlashMessageUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WatchwordImportService
{
    /**
     * current year
     * @var string
     */
    protected $year = '';

    /**
     * @var array|mixed
     */
    protected $settingsArray = [];

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * WatchwordImportService constructor.
     */
    public function __construct()
    {
        $this->year = date('Y');
        // gets default settings from extension configuration
        $this->settingsArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['daily_watchword']);
        $this->settingsArray['downloadURL'] = $this->insertYearInConfigStrings($this->settingsArray['downloadURL']);
        $this->settingsArray['storageFilePath'] = GeneralUtility::getFileAbsFileName($this->insertYearInConfigStrings($this->settingsArray['storageFilePath']));
        $this->settingsArray['fileName'] = $this->insertYearInConfigStrings($this->settingsArray['fileName']);
    }

    /**
     * check settings array for valid inputs
     *
     * @param array $settingsArray
     * @return bool
     * @throws Exception
     */
    protected function checkSettingsArray($settingsArray)
    {
        if (filter_var($settingsArray['downloadURL'], FILTER_VALIDATE_URL) === false
            or empty($settingsArray['downloadURL'])
        ) {
            throw new Exception('Dowload Url not valid. Please check extension settings in the extension manager.');
        }
        if (empty($settingsArray['storageFilePath'])) {
            throw new Exception('Download directory not valid. Please check extension settings in the extension manager.');
        }

        if (empty($settingsArray['fileName'])) {
            throw new Exception('FileName not valid. Please check extension settings in the extension manager.');
        }

        return true;
    }

    /**
     * @param string $inputString
     * @return string
     */
    public function insertYearInConfigStrings($inputString)
    {
        return sprintf($inputString, $this->year);
    }

    /**
     * @param string $downloadUrl
     * @return bool|string
     */
    public function downloadWatchwords($downloadUrl)
    {
        // open curl session
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $downloadUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        $contend = curl_exec($curl);
        curl_close($curl);

        return $contend;
    }

    /**
     * @param string $contend
     * @param string $filePath
     * @return bool
     */
    public function writeToTypo3TempDir($contend, $filePath)
    {
        $result = GeneralUtility::writeFileToTypo3tempDir($filePath, $contend);
        // writeFileToTypo3tempDir gives result NULL on success and an error msg on failure
        return empty($result);
    }

    /**
     * @param string $zipFilePath
     * @param string $fileName
     * @return bool|string
     */
    public function importDataFromZipArchive($zipFilePath, $fileName)
    {
        $handle = zip_open($zipFilePath);
        while (($entry = zip_read($handle)) !== false) {
            if (zip_entry_name($entry) === $fileName) {
                if (zip_entry_open($handle, $entry)) {
                    $contend = zip_entry_read($entry, zip_entry_filesize($entry));
                    zip_entry_close($entry);
                    zip_close($handle);
                    return $contend;
                }
            }
        }
        return false;
    }

    /**
     * encodes String from Windows-1252 to UTF-8
     * @param string $inputString
     * @return string
     */
    public function encondeStringToUTF8($inputString)
    {
        if (mb_detect_encoding($inputString, 'UTF-8', true) === false) {
            $inputString = mb_convert_encoding($inputString, 'UTF-8', 'Windows-1252');
        }
        return $inputString;
    }

    /**
     * @param string $inputString
     * @return string
     */
    protected function replaceSlashes($inputString)
    {
        $outputString = str_replace('/', ' ', $inputString);
        return $outputString;
    }

    /**
     * replace all unicode line separators with unix line separators
     * @param string $inputString
     * @return string
     */
    public function replaceLineSeparators($inputString)
    {
        // double quotes are important for new line char "\n"
        $outputString = preg_replace('/\R/', "\n", $inputString);
        return $outputString;
    }

    /**
     * @param string $CSVString
     * @return array
     */
    public function parseCSVStringToArrayOfCSVRows($CSVString)
    {
        // double quotes are important for new line char "\n"
        $CSVArray = str_getcsv($CSVString, "\n");
        return $CSVArray;
    }

    /**
     * @param array $CSVRowArray
     * @return array
     */
    protected function importWatchwordFromCSVArrayOfRows($CSVRowArray)
    {
        // result counts the failures of database insert
        $errors = [
            'errorCount' => 0,
            'datesThatFailed' => '',
        ];
        foreach ($CSVRowArray as $currentCSVRow) {
            // splits the tab separated contend of the row
            $currentRowArray = str_getcsv($currentCSVRow, chr(9));
            if (is_array($currentRowArray)) {
                $result = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('tx_dailywatchword_domain_model_watchword')
                    ->Insert(
                        'tx_dailywatchword_domain_model_watchword',
                        [
                            'date' => $currentRowArray[0],
                            'weekday' => $currentRowArray[1],
                            'sunday_message' => $currentRowArray[2],
                            'watchwordVerse' => $currentRowArray[3],
                            'watchwordText' => $currentRowArray[4],
                            'teachVerse' => $currentRowArray[5],
                            'teachText' => $currentRowArray[6],
                        ]
                    );
                if ($result === false) {
                    $errors['errorCount']++;
                    $errors['datesThatFailed'] .= ', ' . $currentRowArray[0];
                }
            }
        }
        return $errors;
    }

    /**
     * @return bool
     */
    public function importWatchwords()
    {
        // Instancing
        $flashMessages = GeneralUtility::makeInstance(DailyWatchwordFlashMessageUtility::class);
        if (!is_file($this->settingsArray['storageFilePath'])) {
            $this->checkSettingsArray($this->settingsArray);
            // Download
            $downloadedData = $this->downloadWatchwords($this->settingsArray['downloadURL']);
            // Error msg if download fails
            if ($downloadedData === false) {
                $GLOBALS['BE_USER']->simplelog('Download of Watchwords failed in Download Watchwords task', 'daily_watchword', 3);
                $flashMessages->downloadFailed();
                return false;
            }

            // Saving to file
            $result = $this->writeToTypo3TempDir($downloadedData, $this->settingsArray['storageFilePath']);
            // Error msg if file saving fails
            if ($result === false) {
                $GLOBALS['BE_USER']->simplelog('Writing in TYPO3 temp directory failed in Download Watchwords task', 'daily_watchword', 3);
                $flashMessages->savingToT3TempDirFailed();
                return false;
            }
        }

        // import from zip File
        $contend = $this->importDataFromZipArchive($this->settingsArray['storageFilePath'],
            $this->settingsArray['fileName']);
        // Error msg if import fails
        if ($contend === false) {
            $GLOBALS['BE_USER']->simplelog('Import from Zip archive failed in Download Watchwords task', 'daily_watchword', 3);
            $flashMessages->importFromZipFileFailed();
            return false;
        }

        // processing of data
        $UTF8contend = $this->encondeStringToUTF8($contend);
        $UTF8contend = $this->replaceSlashes($UTF8contend);
        $UTF8contend = $this->replaceLineSeparators($UTF8contend);
        $contendArray = $this->parseCSVStringToArrayOfCSVRows($UTF8contend);

        // database insertion
        $result = $this->importWatchwordFromCSVArrayofRows($contendArray);
        // Error msg if database insertion fails
        if ($result['errorCount'] > 0) {
            $flashMessages->dbInsertionFailed($result);
            return false;
        }
        // Import successfull
        $GLOBALS['BE_USER']->simplelog('Import of Watchwords sucessfull', 'daily_watchword', 0);
        $flashMessages->importSuccessful();

        return true;
    }
}
