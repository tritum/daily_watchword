
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cd6835f1a9a74134928efa89a855f1d0)](https://www.codacy.com/app/TritumFlinke/daily_watchword?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=tritum/daily_watchword&amp;utm_campaign=Badge_Grade)

TYPO3 Extension daily_watchword
===============================

The extension can display the watchword of the day from the Herrnhuter Brüdergemeine `<http://www.ebu.de>`.
It shows the current time and two verses from the bible. One from the Old and one from the New Testament.

The extension is officially authorized by the Evangelische Brüder-Unität Herrnhuter Brüdergemeine.

Requirements
------------

- TYPO3 8.7

Installation
------------

1) Simply download the extension from the TYPO3 TER extension repository in the Backend.
Alternatively you can download the extension from git hub, copy it into the typo3conf/ext/ Directory and activated it in the extension Manager.

2) Set up scheduler task "Download Watchwords". The extension downloads the watchwords for the for the whole current year.
   You can set it up automatically recurring 1-3 times a day. It downloads the new watchwords at on the first day of the new year.
   Alternatively you can activate it manually once a year.
   Every time it runs, it checks if there is a watchword for today in the database and if not, its starts the import process.

Usage
-----

1) Add it in the backend as a frontend plug-in wherever you wish.

Unit Tests
----------

This extension uses nimut/testing-framework.
To run Unit tests use: "composer install -dev" in the extension directory. Then to run Unit tests use:
"TYPO3_PATH_WEB="$PWD/.Build/Web" .Build/bin/phpunit --colors -c .Build/vendor/nimut/testing-framework/res/Configuration/UnitTests.xml Tests/Unit/"