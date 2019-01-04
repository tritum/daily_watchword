[![Build Status](https://travis-ci.org/tritum/daily_watchword.svg?branch=master)](https://travis-ci.org/tritum/daily_watchword)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/daa5737f330d463db690578bcd1e9a32)](https://www.codacy.com/app/tritum/daily_watchword?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=tritum/daily_watchword&amp;utm_campaign=Badge_Grade)

TYPO3 Extension daily_watchword
===============================

Version 1.0.3

The extension displays the watchword of the day from the Herrnhuter Brüdergemeine `<http://www.ebu.de>`.
It shows the current date and two verses from the bible. One from the Old and one from the New Testament.

The extension is officially authorized by the Herrnhuter Brüdergemeine.

Requirements
------------

- TYPO3 8.7

Installation
------------

1) Simply download the extension from GitHub and upload it to your TYPO3 installation via the extension manager.

2) Create a new scheduler task "Download Watchwords". The extension downloads the watchwords for the current year.
There is no need to run the task on a regular base. It is enough to run it manually once a year. Every time it runs,
it checks if there is a watchword for today in the database and if not, its starts the import process.

Usage
-----

Add a frontend plugin to a page of your choice.

Unit Tests
----------

This extension uses nimut/testing-framework.
To run Unit tests use: "composer install -dev" in the extension directory. To run the Unit tests use:
"TYPO3_PATH_WEB="$PWD/.Build/Web" .Build/bin/phpunit --colors -c .Build/vendor/nimut/testing-framework/res/Configuration/UnitTests.xml Tests/Unit/"