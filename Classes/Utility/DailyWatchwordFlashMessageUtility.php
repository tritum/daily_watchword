<?php

namespace Tritum\DailyWatchword\Utility;

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

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DailyWatchwordFlashMessageUtility
{
    /**
     * @var object
     */
    protected $flashMessageService;

    /**
     * @var
     */
    protected $messageQueue;

    /**
     * DailyWatchwordFlashMessageUtility constructor.
     */
    public function __construct()
    {
        $this->flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $this->messageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
    }

    /**
     * show download failed flash msg
     */
    public function downloadFailed()
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
            'Download of Watchwords was unsuccessful check download Path',
            'Scheduler task failed',
            FlashMessage::ERROR);
        $this->messageQueue->addMessage($message);
    }

    /**
     * show 'Saving to TYPO3 Temp directory failed' flash msg
     */
    public function savingToT3TempDirFailed()
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
            'Saving to TYPO3 Temp directory failed',
            'Scheduler task failed',
            FlashMessage::ERROR);
        $this->messageQueue->addMessage($message);
    }

    /**
     * show import from zip file failed flash msg
     */
    public function importFromZipFileFailed()
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
            'Import of Watchword from Zip file failed check File Name',
            'Scheduler task failed',
            FlashMessage::ERROR);
        $this->messageQueue->addMessage($message);
    }

    /**
     * shows Db insertion error flash msg
     * @param array $result | with 2 arguments int $result['errorCount'] and string $result['datesThatFailed']
     */
    public function dbInsertionFailed($result)
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
            $result['errorCount'] . ' failures in Database insertion. Dates that failed: ' - $result['datesThatFailed'],
            'Database Import failed',
            FlashMessage::WARNING);
        $this->messageQueue->addMessage($message);
    }

    /**
     * shows import successful flash msg
     */
    public function importSuccessful()
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
            'Import was successful',
            'Scheduler task successful',
            FlashMessage::OK);
        $this->messageQueue->addMessage($message);
    }
}
