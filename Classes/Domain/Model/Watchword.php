<?php

namespace Tritum\DailyWatchword\Domain\Model;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Watchword extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var array
     */
    protected $watchwordArray = [];

    /**
     * @return array
     */
    public function getWatchwordArray()
    {
        return $this->watchwordArray;
    }

    /**
     * @param array $watchwordArray
     */
    public function setWatchwordArray($watchwordArray)
    {
        $this->watchwordArray = $watchwordArray;
    }

    /**
     * @param string $date | 'd.m.Y' format
     * @return object
     */
    public function readCurrentWatchword($date)
    {
        $row = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_dailywatchword_domain_model_watchword')
            ->select(
                ['date', 'weekday', 'sunday_message', 'watchwordVerse', 'watchwordText', 'teachVerse', 'teachText'],
                'tx_dailywatchword_domain_model_watchword',
                ['date' => $date]
            )
            ->fetch();
        $this->watchwordArray = $row;
    }

    /**
     * __call is executed when an inaccessible method is invoked
     * fluid ivokes f.i. getDate, that is not found then __call is invoked
     * get... is evaluated and the value is returned from the modelArray[...]
     * @param string $name
     * @param array $arguments
     * @return string|null
     */
    public function __call($name, $arguments)
    {
        if ((substr($name, 0, 3)) === 'get') {
            $variable = substr($name, 3);
            $variable[0] = strtolower($variable[0]);
            return $this->watchwordArray[$variable];
        }
    }
}
