<?php

namespace Tritum\DailyWatchword\Task;

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
use Tritum\DailyWatchword\Utility\DatabaseChecksAndDateUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ImportWatchwordTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * @return bool
     */
    public function execute()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $databaseAndDateUtility = $objectManager->get(DatabaseChecksAndDateUtility::class);

        // Check if the Watchword of today is available in Database
        if (!$databaseAndDateUtility->checkIfDateExitsInDB()) {
            $importService = $objectManager->get(WatchwordImportService::class);
            $importService->importWatchwords();
        }
        return true;
    }
}
