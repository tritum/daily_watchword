<?php

namespace Tritum\DailyWatchword\Test\Unit\Domain\Service;

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

use Tritum\DailyWatchword\Service\WatchwordImportService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WatchwordImportServiceTest extends \Nimut\TestingFramework\TestCase\UnitTestCase
{
    /**
     * @var Object
     */
    protected $importService;

    /**
     * sets up new ImportService instance for every test method
     */
    protected function setUp()
    {
        $this->importService = new WatchwordImportService();
    }

    /**
     * @test
     */
    public function downloadOfWatchwordsReturnIsNotEmpty()
    {
        $downloadContend = $this->importService->downloadWatchwords('http://www.losungen.de/fileadmin/media-losungen/download/Losung_' . date('Y') . '_CSV.zip');
        $this->assertNotEmpty($downloadContend);
        return $downloadContend;
    }

    /**
     * @test
     * @depends downloadOfWatchwordsReturnIsNotEmpty
     */
    public function writingToTypo3TempDirReturnTrue($testWatchwords)
    {
        $filePath = GeneralUtility::getFileAbsFileName('typo3temp/var/tests/Losung_' . date('Y') . '_CSV.zip');
        $this->importService->writeToTypo3TempDir($testWatchwords, $filePath);
        $this->assertTrue(is_file($filePath));
        return $filePath;
    }

    /**
     * @test
     * @depends writingToTypo3TempDirReturnTrue
     */
    public function importDataFromZipArchiveReturnsContend($filePath)
    {
        $result = $this->importService->importDataFromZipArchive($filePath, 'Losungen Free ' . date('Y') . '.csv');
        $this->assertTrue(false !== $result);
        $this->testFilesToDelete[] = $filePath;
        return $result;
    }

    /**
     * @test
     * @depends importDataFromZipArchiveReturnsContend
     */
    public function detectEncodingReturnsFalse($inputString)
    {
        $encoding = mb_detect_encoding($inputString, mb_list_encodings());
        $encodedTest = mb_convert_encoding($inputString,'UTF-8', $encoding);

        $this->assertEquals('UTF-8', mb_detect_encoding($encodedTest, mb_list_encodings(), true));
    }

    /**
     *@test
     */
    public function replacementOfLineSeparatorsReturnsStringWithOnlyUnixLineSeparators()
    {
        // double quotes are important for new line char "\n"
        $testString = "Dies\rist\nein\r\nTest.";
        $testString = $this->importService->replaceLineSeparators($testString);
        $expectedString = "Dies\nist\nein\nTest.";
        $this->assertEquals($expectedString, $testString);
    }

    /**
     *@test
     */
    public function parseCSVStringIntoArrayOfRowsReturnsArrayOfRows()
    {
        $testString = "Dies\nist\nein\nTest.";
        $expectedResult = ['Dies', 'ist', 'ein', 'Test.'];
        $testString = $this->importService->parseCSVStringToArrayOfCSVRows($testString);
        $this->assertEquals($expectedResult, $testString);
    }
}
